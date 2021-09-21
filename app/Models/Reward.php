<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'price', 'point', 'status'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'point' => 'integer',
        'price' => 'integer'
    ];

    public static function boot() {
        parent::boot();
        static::saving(function ($model) {
            $model->price = (int) floor(preg_replace('/[Rp. ]/', '', $model->point * 1000));
        });
    }
}
