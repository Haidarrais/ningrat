<?php

namespace App\Http\Livewire;

use App\Http\Controllers\Web\DiscountController;
use Livewire\Component;
use Cart;
use Illuminate\Support\Facades\Http;
use App\Models\Province;
use App\Models\City;
use App\Models\MasterDiscount;
use App\Models\Subdistrict;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Member;
use App\Models\Royalty;
use Illuminate\Support\Facades\Auth;

class CheckoutReseller extends Component
{
    public $ongkir;
    public $enpoint;
    public $courier = null;
    public $othercourier = null;
    public $kurir;
    public $berat;
    public $harga;
    public $numeric = "1234567890";
    public $locations;
    public $province;
    public $cities;
    public $discount, $discountId = 0, $discounts, $discountOn, $discountNominal, $subtotal;
    public $city;
    public $subdistricts;
    public $subdistrict;
    public $sellerlocation;
    public $sellerid;
    public $buyers;
    public $buyer_name;
    public $buyer_phone;
    public $buyer_address;

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
                'destination'       => $this->subdistrict, // Tujuan Id Kota atau id Kecamatan
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
        if ($this->courier != ' ') {
            $kurir = $this->courier;
        }else{
            $kurir = $this->othercourier;
        }
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
        $royalty = Royalty::latest()->first();
        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'seller_id' => $this->sellerid,
            'city_id' => $this->city,
            'invoice' => $invoice,
            'sendto' => $this->buyer_name,
            'cost' => $this->ongkir,
            'shipping' => $kurir,
            'member_name' =>  Auth::user()->name,
            'member_phone' =>   $this->buyer_phone,
            'member_address' => $this->buyer_address,
            'subtotal' => $subtotal,
            'status' => 0,
            'royalty' => $royalty ?? 10
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
        return redirect()->to('/reseller/profile');
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
        $this->locations = Province::all();
        if ($this->province) {
            $this->cities = City::where('province_id', $this->province)->get();
            if ($this->cities) {
                $this->subdistricts = Subdistrict::where('city_id', $this->city)->get();
            }
        }
        return view('livewire.checkout-reseller')->layout('layouts.main');
    }
}
