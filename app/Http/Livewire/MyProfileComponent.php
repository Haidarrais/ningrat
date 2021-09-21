<?php

namespace App\Http\Livewire;

use App\Models\Transaction;
use App\Models\Province;
use App\Models\City;
use App\Models\Stock;
use App\Models\Subdistrict;
use App\Models\TransactionDetail;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class MyProfileComponent extends Component
{
    public $status;
    public $province;
    public $city;
    public $subdistrict;
    public $subdistricts;
    public $locations;
    public $cities;
    public $waybill=array();
    public $transactions;
    public function mount()
    {
        $this->province = Auth::user()->member->city->province_id ?? " ";
        $this->city = Auth::user()->member->city_id ?? " ";
        $this->subdistrict = Auth::user()->member->subdistrict_id ?? " ";
    }
    public function lacak($id) {
        $type = strtolower(env('RAJAONGKIR_PACKAGE', 'Key Dari ENV'));
        $enpoint = "https://".$type.".rajaongkir.com/api/";
        $transaction = Transaction::where('id', $id)->first();
        $http = Http::post($enpoint."waybill", [
            'key'               => env('RAJAONGKIR_API_KEY', 'Key Dari ENV'),
            'waybill'           => $transaction->waybill, // Resi
            'courier'           => $transaction->shipping, // Kurir
        ]);
        $res = $http['rajaongkir'];
        $result = $res['result'];
        return view('livewire.my-orders-component', compact('result', 'transaction'))->layout('layouts.main');
    }
    public function setToDone($id) {
        Transaction::where('id', $id)->update(['status' => 4]);
        $stock = TransactionDetail::where('transaction_id', $id)->get();
        foreach ($stock as $stck) {
            $state = Stock::where('id', $stck->stock_id)->first();
            $final = $state->stock - $stck->qty;
            $state = Stock::where('id', $stck->stock_id)->update(['stock' => $final]);
        }
    }
    public function render()
    {
        $userid = Auth::user()->id;
        $this->locations = Province::all();
        $this->cities = City::where('province_id', $this->province)->get();
        $this->subdistricts = Subdistrict::where('city_id', $this->city)->get();
        $this->transactions = Transaction::where('user_id', $userid)->get();
        //return dd($this->subdistricts);
        return view('livewire.my-profile-component')->layout('layouts.main');
    }
}
