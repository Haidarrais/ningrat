<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Point extends Model
{
    use HasFactory;
    protected $fillable = ["category_id", "min", "point"];
    protected $with = ['category'];

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
