<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Invoice;

class TransactionController extends Controller
{

    private $numeric = "1234567890";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $user = Auth::user();
        $role = $user->getRoleNames()->first();
        $bawahan = User::where('upper', $user->id)->get()->pluck('id')->toArray();
        $products = Product::all();
        $query = Transaction::query();
        $query->with('details');
        if($role == 'superadmin') {
            // return "Welkom to mai yutup cenel";
        } else {
            $query->where('seller_id', $user->id);
        }
        $transactions = $query->paginate(10);
        $upper_origin = [];
        $upper = User::with('member')->find($user->upper);
        if($upper) {
            $upper_origin = [

                'city' => $upper->member->city_id,
                'subdistrict' => $upper->member->subdistrict_id
            ];
        }
        if($request->ajax()) {
            return view('pages.order.transaction.pagination', compact('transactions', 'data'))->render();
        }
        return view('pages.order.transaction.index', compact('transactions', 'data', 'products', 'upper_origin'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $user_id = Auth::user()->id;
            $user = User::with('member')->find($user_id);
            $data = $request->all();
            if(!$user->member->city_id) {
                return response()->json([
                    'status' => true,
                    'message' => [
                        'head' => 'Gagal',
                        'body' => 'Silahkan update kota anda'
                    ]
                ], 500);
            }
            $price = 0;
            $invoice = "INV-".date('Ymd').substr(str_shuffle($this->numeric), 0, 12);
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'city_id' => $user->member->city_id,
                'invoice' => $invoice,
                'member_name' => $user->name,
                'member_phone' => $user->member->phone_number,
                'member_address' => $user->member->address,
                'subtotal' => 0,
                'cost' => $data['cost'],
                'shipping' => $data['courier'],
                'status' => 0
            ]);
            $transaction_details = [];
            foreach ($data['id'] as $key => $value) {
                if($data['qty'][$key] == 0) continue;
                $product = Product::find($value);
                $temp_data = [];
                $temp_data['transaction_id'] = $transaction->id;
                $temp_data['product_id'] = $product->id;
                $temp_data['price'] = $product->price;
                $temp_data['weight'] = $product->weight;
                $temp_data['qty'] = $data['qty'][$key];
//                $temp_data['created_at'] = now();
//                $temp_data['updated_at'] = now();
                $price += $product->price * $data['qty'][$key];
                array_push($transaction_details, $temp_data);
            }
            TransactionDetail::insert($transaction_details);
            $transaction->update([
                'subtotal' => $price
            ]);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => [
                    'head' => 'Berhasil',
                    'body' => 'Transaction Berhasil dibuat'
                ]
            ], 200);
        } catch(\Exception $e) {
            return response()->json([
                'status' => true,
                'message' => [
                    'head' => 'Gagal',
                    'body' => $e->getMessage()
                ]
            ], 500);
            DB::rollBack();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $transaction = Transaction::with(['user.member.city.province', 'details.stock.product'])->find($id);
        return response()->json([
            'status' => true,
            'data' => $transaction
        ], 200);
    }

    public function show_resi($id) {
        $transaction = Transaction::find($id);
        return response()->json([
            'status' => true,
            'data' => $transaction
        ], 200);
    }

    public function set_resi(Request $request, $id) {
        $transaction = Transaction::find($id);
        $transaction->update([
            'waybill' => $request->waybill
        ]);
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Berhasil',
                'body' => 'Resi Berhasil di update'
            ]
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function set_status($id, $status) {
        $transaction = Transaction::find($id);
        $transaction->update([
            'status' => $status
        ]);
        if($status == 1) {
            $text = "Dikemas";
        } else if($status == 2) {
            $text = "Dikirim";
        } else if($status == 3) {
            $text = "Diterima";
        } else if($status == 4) {
            $text = "Selsai";
            foreach ($transaction->details()->get() as $key => $value) {
                $stock = Stock::where('user_id', $transaction->user_id)
                                ->where('product_id', $value->product_id)
                                ->first();
                if($stock) {
                    $temp_total = $stock->stock ?? 0;
                    $stock->update([
                        'stock' => $value->qty + $temp_total
                    ]);
                } else {
                    Stock::create([
                        'user_id' => $transaction->user_id,
                        'product_id' => $value->product_id,
                        'stock' => $value->qty,
                        'status' => 1
                    ]);
                }
            }
        } else if($status == 5) {
            $text = "Ditolak";
        } else if($status == 6) {
            $text = "DiBatalkan";
        }
        return response()->json([
            'status' => true,
            'message' => [
                'head' => 'Berhasil',
                'body' => 'Transaction Telah '. $text
            ]
        ], 200);
    }

    public function printInvoice(){
        $client = new Party([
            'name'          => 'Roosevelt Lloyd',
            'phone'         => '(520) 318-9486',
            'custom_fields' => [
                'note'        => 'IDDQD',
                'business id' => '365#GG',
            ],
        ]);

        $customer = new Party([
            'name'          => 'Ashley Medina',
            'address'       => 'The Green Street 12',
            'code'          => '#22663214',
            'custom_fields' => [
                'order number' => '> 654321 <',
            ],
        ]);

        $items = [
            (new InvoiceItem())
                ->title('Service 1')
                ->description('Your product or service description')
                ->pricePerUnit(47.79)
                ->quantity(2)
                ->discount(10),
            (new InvoiceItem())->title('Service 2')->pricePerUnit(71.96)->quantity(2),
            (new InvoiceItem())->title('Service 3')->pricePerUnit(4.56),
            (new InvoiceItem())->title('Service 4')->pricePerUnit(87.51)->quantity(7)->discount(4)->units('kg'),
            (new InvoiceItem())->title('Service 5')->pricePerUnit(71.09)->quantity(7)->discountByPercent(9),
            (new InvoiceItem())->title('Service 6')->pricePerUnit(76.32)->quantity(9),
            (new InvoiceItem())->title('Service 7')->pricePerUnit(58.18)->quantity(3)->discount(3),
            (new InvoiceItem())->title('Service 8')->pricePerUnit(42.99)->quantity(4)->discountByPercent(3),
            (new InvoiceItem())->title('Service 9')->pricePerUnit(33.24)->quantity(6)->units('m2'),
            (new InvoiceItem())->title('Service 11')->pricePerUnit(97.45)->quantity(2),
            (new InvoiceItem())->title('Service 12')->pricePerUnit(92.82),
            (new InvoiceItem())->title('Service 13')->pricePerUnit(12.98),
            (new InvoiceItem())->title('Service 14')->pricePerUnit(160)->units('hours'),
            (new InvoiceItem())->title('Service 15')->pricePerUnit(62.21)->discountByPercent(5),
            (new InvoiceItem())->title('Service 16')->pricePerUnit(2.80),
            (new InvoiceItem())->title('Service 17')->pricePerUnit(56.21),
            (new InvoiceItem())->title('Service 18')->pricePerUnit(66.81)->discountByPercent(8),
            (new InvoiceItem())->title('Service 19')->pricePerUnit(76.37),
            (new InvoiceItem())->title('Service 20')->pricePerUnit(55.80),
        ];

        $notes = [
            'your multiline',
            'additional notes',
            'in regards of delivery or something else',
        ];
        $notes = implode("<br>", $notes);

        $invoice = Invoice::make('receipt')
            ->series('BIG')
            // ability to include translated invoice status
            // in case it was paid
            ->status(__('invoices::invoice.paid'))
            ->sequence(667)
            ->serialNumberFormat('{SEQUENCE}/{SERIES}')
            ->seller($client)
            ->buyer($customer)
            ->date(now()->subWeeks(3))
            ->dateFormat('m/d/Y')
            ->payUntilDays(14)
            ->currencySymbol('$')
            ->currencyCode('USD')
            ->currencyFormat('{SYMBOL}{VALUE}')
            ->currencyThousandsSeparator('.')
            ->currencyDecimalPoint(',')
            ->filename($client->name . ' ' . $customer->name)
            ->addItems($items)
            ->notes($notes)
            ->logo(public_path('vendor/invoices/sample-logo.png'))
            // You can additionally save generated invoice to configured disk
            ->save('public');

        $link = $invoice->url();
        // Then send email to party with link

        // And return invoice itself to browser or have a different view
        return $invoice->stream();
    }
}
