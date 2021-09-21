<?php

namespace App\Http\Livewire;

use App\Models\TransactionDetail;
use App\Models\Transaction;
use Livewire\Component;

class DetailOrderComponent extends Component
{
    public $transaction;
    public $transactions;
    public $invoice;

    public function mount($transaction)
    {
        $this->transaction = $transaction;
        $this->transactions = TransactionDetail::where('transaction_id', '=', $this->transaction)->get();
        $this->invoice = Transaction::where('id', '=', $this->transaction)->get()->toArray();
    }
    public function render()
    {
        return view('livewire.detail-order-component')->layout('layouts.main');
    }
}
