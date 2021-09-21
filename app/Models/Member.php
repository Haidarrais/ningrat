<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'city_id',
        'subdistrict_id',
        'phone_number',
        'address'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function city() {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function subdistrict() {
        return $this->belongsTo(Subdistrict::class, 'subdistrict_id');
    }
}
