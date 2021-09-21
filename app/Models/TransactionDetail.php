<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function transaction() {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }
    public function stock() {
        return $this->belongsTo(Stock::class, 'stock_id');
    }
}
