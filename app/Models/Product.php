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

}
