<?php

namespace App\Http\Livewire;

use App\Models\MasterDiscount;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Member;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Cart;
use Illuminate\Support\Facades\Http;


class CheckoutComponent extends Component
{
    public Transaction $transaction;
    public $buyers;
    public $sellerlocation;
    public $sellerid;
    public $numeric = "1234567890";
    public $discount, $discountId = 0, $discounts, $discountOn, $discountNominal, $subtotal;
    public $ongkir;
    public $berat;
    public $courier = null;
    public $othercourier = null;
    public $harga;
    public $type;
    public $enpoint;
    public $kurir;

    protected $rules = [
        'ongkir' => 'required',
    ];

    public function mount()
    {
        $this->type = strtolower(env('RAJAONGKIR_PACKAGE', 'Key Dari ENV'));
        $this->enpoint = "https://".$this->type.".rajaongkir.com/api/";
        $this->kurir = ['jne', 'pos', 'wahana', 'jnt', 'sap', 'sicepat', 'jet', 'dse', 'first', 'ninja', 'lion', 'idl', 'rex', 'ide', 'sentral', 'anteraja'];
    }
    public function ongkir($ongkir)
    {
        $this->ongkir = $ongkir;
        $this->alert('success', 'Berhasil Menambahkan Ongkir!', [
            'position' =>  'center',
            'timer' =>  3000,
            'toast' =>  true,
            'text' =>  '',
            'confirmButtonText' =>  'Ok',
            'cancelButtonText' =>  'Cancel',
            'showCancelButton' =>  false,
            'showConfirmButton' =>  false,
      ]);
    }
    public function getOngkir()
    {
        if ($this->courier != '1') {
            $http = Http::post($this->enpoint."cost", [
                'key'               => env('RAJAONGKIR_API_KEY', 'Key Dari ENV'),
                'origin'            => $this->sellerlocation, // Asal Id Kota atau id Kecamatan
                'originType'        => "subdistrict", // city atau subdistrict
                'destination'       => $this->buyers->subdistrict_id, // Tujuan Id Kota atau id Kecamatan
                'destinationType'   => "subdistrict", // city atau subdistrict
                'weight'            => $this->berat, // Berat dalam gram
                'courier'           => $this->courier // Kode Kurir
            ]);
        $result = $http['rajaongkir'];
        return $this->harga = $result['results'];
        }
    }
    public function save()
    {
        $this->validate();
        $invoice = "INV-".date('Ymd').substr(str_shuffle($this->numeric), 0, 12);
        if ($this->discountOn) {
            if ($this->discountId) {
                $userId = Auth::id();
                $discount = MasterDiscount::find($this->discountId);
                $userList = json_decode($discount->userList);
                if ($userList == null) {
                    $discount->userList = json_encode([$userId]);
                }else{
                    foreach ($userList as $value) {
                        if ($value == $userId) {
                            $this->alert('error', 'gagal user sudah menggunakan diskon ini', [
                                'position' =>  'center',
                                'timer' =>  3000,
                                'toast' =>  true,
                                'text' =>  '',
                                'confirmButtonText' =>  'Ok',
                                'cancelButtonText' =>  'Cancel',
                                'showCancelButton' =>  false,
                                'showConfirmButton' =>  false,
                            ]);
                        }
                    }
                    array_push($userList, $userId);
                    $discount->update(['userList' => $userList]);
                }
                $this->alert('sukses', 'berhasil', [
                    'position' =>  'center',
                    'timer' =>  3000,
                    'toast' =>  true,
                    'text' =>  '',
                    'confirmButtonText' =>  'Ok',
                    'cancelButtonText' =>  'Cancel',
                    'showCancelButton' =>  false,
                    'showConfirmButton' =>  false,
                ]);
            }
        $subtotal = $this->subtotal+$this->ongkir;
        }else{
            $subtotal = Cart::subtotal(2,'.','')+$this->ongkir;
        }
        $transaction = Transaction::create([
            'user_id' => $this->buyers->user->id,
            'seller_id' => $this->sellerid,
            'city_id' => $this->buyers->city_id,
            'invoice' => $invoice,
            'sendto' => $this->buyers->user->name,
            'member_name' =>  $this->buyers->user->name,
            'member_phone' =>   $this->buyers->phone_number,
            'member_address' => $this->buyers->address,
            'subtotal' => $subtotal,
            'shipping' => $this->courier,
            'cost' => $this->ongkir,
            'discount' => $this->discount->discount ?? 0,
            'status' => 0
        ]);
        // dd(Cart::content());
        foreach (Cart::content() as $cart) {
            TransactionDetail::create([
            'transaction_id' => $transaction->id,
            'stock_id' => $cart->id,
            'price' => $cart->price,
            'weight' => $cart->model->product->weight,
            'qty' => $cart->qty,
            'note' => $cart->options["note"],
            ]);
        }
        if ($this->discountOn) {
            $discount->save();
        }
        Cart::destroy();
        $this->flash('info', 'Pembelian berhasil silahkan melakukan pembayaran!', [
            'position' =>  'center',
            'timer' =>  3000,
            'toast' =>  true,
            'text' =>  '',
            'confirmButtonText' =>  'Ok',
            'cancelButtonText' =>  'Cancel',
            'showCancelButton' =>  false,
            'showConfirmButton' =>  false,
        ]);
        return redirect()->to('/customer/profile');
    }
    public function discountTrigger()
    {
        if ($this->discountOn) {
            $this->alert('success', 'berhasil diskon sedang aktif', [
                'position' =>  'center',
                'timer' =>  3000,
                'toast' =>  true,
                'text' =>  '',
                'confirmButtonText' =>  'Ok',
                'cancelButtonText' =>  'Cancel',
                'showCancelButton' =>  false,
                'showConfirmButton' =>  false,
            ]);
        }else{
            $this->alert('info', 'diskon dinonaktifkan', [
                'position' =>  'center',
                'timer' =>  3000,
                'toast' =>  true,
                'text' =>  '',
                'confirmButtonText' =>  'Ok',
                'cancelButtonText' =>  'Cancel',
                'showCancelButton' =>  false,
                'showConfirmButton' =>  false,
            ]);
        }
    }
    public function render()
    {
        if ($this->discountId) {
            $this->discount = MasterDiscount::find($this->discountId);
        }
        $this->discounts = MasterDiscount::all();
        if ($this->othercourier!=null) {
            $this->ongkir = 1;
        }
        $buyer = Auth::id();
        $buyers = Member::where('user_id', $buyer)->first();
        $this->buyers = $buyers;
        return view('livewire.checkout-component', ['buyers' => $buyers])->layout('layouts.main');
    }
}
