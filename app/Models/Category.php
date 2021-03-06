<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function boot() {
        parent::boot();
        static::saving(function ($model) {
            $model->slug = Str::slug($model->name);
        });
    }

    public function product()
    {
        return $this->hasMany(Product::class);
    }
    public function variant()
    {
        return $this->hasMany(Variant::class);
    }
    public function subCategory()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }
    public function discount(){
        return $this->hasOne(CategoryDiscount::class);
    }
}
