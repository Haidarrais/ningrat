<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;

    protected $primaryKey = 'city_id';
    protected $guarded = [];
    protected $with = ['province', 'subdistrict'];

    public function province() {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function subdistrict() {
        return $this->hasMany(Subdistrict::class, 'city_id');
    }
}
