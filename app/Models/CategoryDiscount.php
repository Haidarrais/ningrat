<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryDiscount extends Model
{
    use HasFactory;
    protected $fillable = ["category_id", "value"];


    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }
}
