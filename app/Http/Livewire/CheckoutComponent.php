<?php

namespace App\Http\Livewire;
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
    public $discount, $discounts, $discountOn, $discountNominal, $subtotal;
    public $ongkir;
    public $berat;
    public $courier = null;
    public $othercourier = null;
    public $harga;
    public $type;
    public $enpoint;
    public $kurir;
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
        $invoice = "INV-".date('Ymd').substr(str_shuffle($this->numeric), 0, 12);
        if ($this->discountOn) {
            if ($this->discount) {
                $userId = Auth::id();
                $discount = MasterDiscount::find($this->discount->id);
                $userList = json_decode($discount->userList);
                if ($userList == null) {
                    $discount->userList = json_encode([$userId]);
                }else{
                    foreach ($userList as $value) {
                        if ($value == $userId) {
                            return response()->json([
                                'status' => 0,
                                'message' => "User sudah menggunakan discount ini"
                            ]);
                        }
                    }
                    array_push($userList, $userId);
                    $discount->update(['userList' => $userList]);
                }
                $discount->save();

                return response()->json([
                    'status' => 1,
                    'data'  => $discount
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
            'status' => 0
        ]);
        foreach (Cart::content() as $cart) {
            TransactionDetail::create([
            'transaction_id' => $transaction->id,
            'stock_id' => $cart->id,
            'price' => $cart->price,
            'weight' => $cart->options->stock->product->weight,
            'qty' => $cart->qty,
            ]);
        }
        Cart::destroy();
        return redirect()->to('/customer/profile');
    }
    public function render()
    {
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
