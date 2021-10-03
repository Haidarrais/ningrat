<?php

namespace App\Http\Livewire;

use App\Models\Courier;
use App\Models\TransactionDetail;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class DetailOrderComponent extends Component
{
    public $transaction, $transactions, $invoice, $waybill, $courier, $type, $enpoint, $result;

    public function mount($transaction)
    {
        $this->type = strtolower(env('RAJAONGKIR_PACKAGE', 'Key Dari ENV'));
        $this->enpoint = "https://".$this->type.".rajaongkir.com/api/";
        $this->transaction = $transaction;
        $this->transactions = TransactionDetail::where('transaction_id', '=', $this->transaction)->get();
        $this->invoice = Transaction::where('id', '=', $this->transaction)->get()->toArray();
        $this->waybill = $this->invoice[0]['waybill'];
        $this->courier = $this->invoice[0]['shipping'];
    }
    public function lacak() {
        $http = Http::post($this->enpoint."waybill", [
            'key'               => env('RAJAONGKIR_API_KEY', 'Key Dari ENV'),
            'waybill'           => $this->waybill, // Resi
            'courier'           => $this->courier, // Kurir
        ]);
        $result = $http['rajaongkir']['result'];
        return $this->result = $result;
    }
    public function render()
    {
        $this->lacak();
        return view('livewire.detail-order-component')->layout('layouts.main');
    }
}
