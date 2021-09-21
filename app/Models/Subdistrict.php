<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subdistrict extends Model
{
    use HasFactory;

    protected $primaryKey = "subdistrict_id";
    protected $guarded = [];

    public function city() {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function province() {
        return $this->belongsTo(Province::class, 'province_id');
    }
}
