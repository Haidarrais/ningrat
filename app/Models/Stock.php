<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Gloudemans\Shoppingcart\Contracts\Buyable;

class Stock extends Model implements Buyable
{
    use HasFactory;
    protected $fillable = ['user_id', 'product_id', 'status', 'stock', 'member_price', 'type'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'member_price' => 'integer',
        'stock' => 'integer',
    ];
    public function getBuyableIdentifier($options = null){
        return $this->id;
    }

    public function getBuyableDescription($options = null){
        return $this->name;
    }

    public function getBuyablePrice($options = null){
        return $this->price;
    }
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function discount() {
        return $this->hasOne(Discount::class);
    }
}
