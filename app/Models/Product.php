<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'integer',
        'weight' => 'integer',
    ];

    public static function boot() {
        parent::boot();
        static::saving(function ($model) {
            $model->slug = Str::slug($model->name);
        });

        // Setup event bindings...
        static::deleting(function ($model) {
            //delete related  
            if ($model->isInStock()->delete()) {
                return true;
            }
            return false;
        });
    }

    public function variant(){
        return $this->belongsTo(Variant::class);
    }

    public function picture()
    {
        return $this->hasMany(ProductPicture::class) ?? null;
    }

    public function onePicture()
    {
        return $this->hasMany(ProductPicture::class)->oldest();
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function buyed(){
        return $this->hasMany(OrderDetail::class);
    }

    public function isInStock(){
        return $this->hasOne(Stock::class, 'product_id','id')->where('user_id',0);
    }

}
