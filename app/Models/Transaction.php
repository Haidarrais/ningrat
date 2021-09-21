<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'city_id',
        'seller_id',
        'invoice',
        'sendto',
        'cost',
        'shipping',
        'waybill',
        'member_name',
        'member_phone',
        'member_address',
        'subtotal',
        'status'
    ];
    public function seller() {
        return $this->belongsTo(User::class, 'seller_id');
    }
    public function details() {
        return $this->hasMany(TransactionDetail::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getTotalAttribute() {
        return $this->subtotal + $this->cost;
    }

    public function getTanggalPesanAttribute() {
        return Carbon::parse($this->created_at)->format('d F Y H:i:s');
    }
}
