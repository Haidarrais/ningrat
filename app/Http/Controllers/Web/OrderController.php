<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MasterDiscount;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Point;
use App\Models\Setting;
use App\Models\Stock;
use App\Models\User;
use App\Traits\SettingTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use SettingTrait;

    private $numeric = "1234567890";
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $request->all();
        $keyword = $request->keyword;
        $user = Auth::user();
        $role = $user->getRoleNames()->first();
        $bawahan = User::where('upper', $user->id)->get()->pluck('id')->toArray();
        $query = Order::query();
        $query->with('details');
        if($role == 'superadmin') {
        } else {
            $query->where(function($q) use($bawahan, $user) {
                $q->whereIn('user_id', $bawahan)
                    ->orWhere('user_id', $user->id);
            });
        }
        $query->when($keyword, function($q) use($keyword) {
            if($keyword == "Pending") {
                $keyword = 0;
            } else if($keyword == "Dikemas") {
                $keyword = 1;
            } else if($keyword == "Dikirim") {
                $keyword = 2;
            } else if($keyword == "Diterima") {
                $keyword = 3;
            } else if($keyword == "Selesai") {
                $keyword = 4;
            } else if($keyword == "Ditolak") {
                $keyword = 5;
            } else if($keyword == "Batal") {
                $keyword = 6;
            }
            $q->where('member_name', "LIKE", "%$keyword%")
            ->orWhere('member_address', 'LIKE', "%$keyword%")
            ->orWhere('status', 'LIKE', "%$keyword%");
        });
        $orders = $query->latest()->paginate(10);

        if($request->ajax()) {
            return view('pages.order.order.pagination', compact('orders', 'data'))->render();
        }
        return view('pages.order.order.index', compact('orders', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $role = $user->getRoleNames()->first();
        $discount = MasterDiscount::where('status', 1)->latest()->first();
        $user_updated_at = Carbon::createFromFormat('Y-m-d H:i:s', $user->last_upgrade)->year;
        $minimal_transaction = 0;
        $monthly_min_transaction = 0;
        $discount_role_based = 0;
        //get minimal order
        $setting_role = "";
        if($role == 'superadmin') {
        } else if($role == 'distributor') {
            $minimal_transaction = $user_updated_at > 2019 ?
                Setting::where('role', 'new-distributor')->first()->minimal_transaction??0 :
                Setting::where('role', 'old-distributor')->first()->minimal_transaction??0;
                 $discount_role_based= Setting::where('role', 'old-distributor')->first()->discount??0;
            $monthly_min_transaction = Setting::where('role', 'old-distributor')->first()->value??0;
            $products = Product::all();
            $setting_role = $user_updated_at > 2019? "new-distributor": "old-distributor";
        } else {
            $setting_role = $role;
            $minimal_transaction = Setting::where('role', $role)->first()->minimal_transaction ?? 0;
            $monthly_min_transaction = Setting::where('role', $role)->first()->value??0;
            $discount_role_based = Setting::where('role', $role)->first()->discount ?? 0;
            $products = Stock::with(['product', 'user'])->where('user_id', $user->upper)->where('status', 1)->where('stock', '>', 0)->get();
        }
        if ($role == 'reseller') {
            $hirarki = User::where('id', $user->id)->get()->pluck('id')->toArray();
        } else {
            $hirarki = User::where('upper', $user->id)->get()->pluck('id')->toArray();
        }
        $this_month_total_transaction = $this->getMonthTotalTransaction($hirarki,$user,$monthly_min_transaction);
        // dd($this_month_total_transaction);
        $upper_origin = [];
        $upper = User::with('member')->find($user->upper);
        if($upper) {
            $upper_origin = [
                'city' => $upper->member->city_id,
                'subdistrict' => $upper->member->subdistrict_id
            ];
        }
        // dd($products);
        if ($request->ajax()) {
            return $role=="distributor"?view('pages.order.order.paginationdistributor', compact('products', 'upper_origin', 'discount', 'minimal_transaction', 'role', 'this_month_total_transaction', 'monthly_min_transaction', 'discount_role_based', 'setting_role'))->render(): view('pages.order.order.pagination_create_order', compact('products', 'upper_origin', 'discount', 'minimal_transaction', 'role', 'this_month_total_transaction', 'monthly_min_transaction', 'discount_role_based', 'setting_role'))->render();
        }else{
            if($role == 'distributor') {
                return view('pages.order.order.distributor-page', compact('products', 'upper_origin', 'minimal_transaction', 'role','this_month_total_transaction','monthly_min_transaction', 'discount_role_based', 'setting_role'));
            }
            return view('pages.order.order.order-page', compact('products', 'upper_origin', 'discount', 'minimal_transaction', 'role','this_month_total_transaction','monthly_min_transaction', 'discount_role_based', 'setting_role'));
        }
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
            // dd([
            //     'data'=>$data,
            //     'data[]id'=>$data['id']
            // ]);
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
            $invoice = $this->generateInvoice('INV', date('Ymd').substr(str_shuffle($this->numeric), 0, 12));
            $order = Order::create([
                'user_id' => $user->id,
                'city_id' => $user->member->city_id,
                'invoice' => $invoice,
                'member_name' => $user->name,
                'member_phone' => $user->member->phone_number,
                'member_address' => $user->member->address,
                'subtotal' => 0,
                'cost' => $data['cost'],
                'discount'=>$data['discount'],
                'subsidy_cost'=>$data['ongkir-discount'],
                'shipping' => $data['courier'],
                'status' => $data['status'] ?? 0
            ]);
            $order_details = [];
            foreach ($data['id'] as $key => $value) {
                if($data['qty'][$key] == 0) continue;
                $product = Product::find($value);
                $temp_data = [];
                $temp_data['order_id'] = $order->id;
                $temp_data['product_id'] = $product->id;
                // $temp_data['price'] = $product->price;
                $temp_data['price'] = $data['price'][$key];
                $temp_data['weight'] = $product->weight;
                $temp_data['qty'] = $data['qty'][$key];
//                $temp_data['created_at'] = now();
//                $temp_data['updated_at'] = now();
                // $price += $product->price * $data['qty'][$key];
                $price += $data['price'][$key];
                array_push($order_details, $temp_data);
            }
            OrderDetail::insert($order_details);
            $order->update([
                'subtotal' => $price
            ]);
            // Jika Reseller
            if(isset($data['status'])) {
                foreach ($data['id'] as $key => $value) {
                    if($data['qty'][$key] == 0) continue;
                    $product = Product::find($value);
                    $stock_reseller_temp = Stock::where('product_id', $product->id)->where('user_id', $user_id)->first();
                    $stock_now = $stock_reseller_temp->stock - $data['qty'][$key];
                    $stock_reseller_temp->update([
                        'stock' => $stock_now
                    ]);
                }
            }
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => [
                    'head' => 'Berhasil',
                    'body' => 'Order Berhasil dibuat'
                ],
                'redirect' => route('order.index')
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

    public function store_reseller(Request $request) {
        // DB::beginTransaction();
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
            $invoice = $this->generateInvoice('INV', date('Ymd').substr(str_shuffle($this->numeric), 0, 12));

        } catch(\Exception $e) {
            return response()->json([
                'status' => true,
                'message' => [
                    'head' => 'Gagal',
                    'body' => $e->getMessage()
                ]
            ], 500);
            // DB::rollBack();
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
        $order = Order::with(['user.member.city.province', 'details.product.picture'])->find($id);
        return response()->json([
            'status' => true,
            'data' => $order
        ], 200);
    }

    public function show_resi($id) {
        $order = Order::find($id);
        return response()->json([
            'status' => true,
            'data' => $order
        ], 200);
    }

    public function set_resi(Request $request, $id) {
        $order = Order::find($id);
        $order->update([
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
        $order = Order::find($id);
        $order->update([
            'status' => $status == 3 ? 4 : $status
        ]);
        if($status == 1) {
            $text = "Dikemas";
        } else if($status == 2) {
            $text = "Dikirim";
        } else if($status == 3) {
            $text = "Diterima";
        } else if($status == 4) {
            $text = "Selsai";
            foreach ($order->details()->get() as $key => $value) {
                $stock = Stock::where('user_id', $order->user_id)
                                ->where('product_id', $value->product_id)
                                ->first();
                if($stock) {
                    $temp_total = $stock->stock ?? 0;
                    $stock->update([
                        'stock' => $value->qty + $temp_total
                    ]);
                } else {
                    // Type = 1: non-reseller; 2: reseller
                    $type = 2;
                    $user_order = User::find($order->user_id);
                    $role_user_order = $user_order->getRoleNames()->first();
                    if($role_user_order == 'reseller') {
                        $type = 2;
                    }
                    Stock::create([
                        'user_id' => $order->user_id,
                        'product_id' => $value->product_id,
                        'stock' => $value->qty,
                        'status' => 1,
                        'type' => $type
                    ]);
                }
            }

            $min_order = $this->getValue(Setting::all(), 'minimal-belanja-point');
            if($order->subtotal >= (int) $min_order) {
                $point = Point::where('category_id', $value->product->category_id)->first();
                if($point) {
                    $qty = $value->qty;
                    $total = $qty/$point->min;
                    if(floor($total) > 0) {
                        DB::table("point_user")->insert([
                            'point_id' => $point->id,
                            'user_id' => $order->user_id,
                            'total' => floor($total),
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
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
                'body' => 'Order Telah '. $text
            ]
        ], 200);
    }

    private function getMonthTotalTransaction($hirarki, $user, $min_trans_per_month){
        $todaysMonth = Carbon::now()->month;
        $processedOrders = [];
        $orders =  Order::select(
            DB::raw('sum(subtotal) as sums'),
            DB::raw("DATE_FORMAT(created_at,'%m') as month")
        )
            ->orWhere('user_id', $user->id)
            ->where('status', 4)
            ->whereMonth('created_at', $todaysMonth)
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('month')
            ->orderBy('created_at', 'ASC')
            ->get()->toArray();

        if ($todaysMonth > 6) {
            $filteredArray = Arr::where($orders, function ($value, $key) {
                return intval($value['month']) > 6;
            });

            // return $filteredArray;
        } else {
            $filteredArray = Arr::where($orders, function ($value, $key) {
                return intval($value['month']) <= 6;
            });
            // return $filteredArray;
        }
        foreach ($filteredArray as $index => $value) {
            $processedOrders[0] = $value;
        }
        if (count($processedOrders)>0) {
            return intval($processedOrders[0]["sums"])??0;
            # code...
        }else{
            return 0;
        }
    }
}
