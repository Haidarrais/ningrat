<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['total', 'tanggal_pesan'];

    public function details() {
        return $this->hasMany(OrderDetail::class);
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
