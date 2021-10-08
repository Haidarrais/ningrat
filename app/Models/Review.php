<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = [
        'transaction_id',
        'member_id',
        'buyer_id',
        'rating',
        'word'
    ];

    public function transaction(){
        $this->belongsTo(Transaction::class);
    }
    public function member(){
        $this->belongsTo(Member::class);
    }
    public function buyer(){
        $this->belongsTo(Member::class, 'buyer_id');
    }
}
