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

    public function reviewRows()
    {
        return $this->hasMany(Review::class, 'member_id', 'user_id');
    }

    public function avgRating()
    {
        return $this->reviewRows()
        ->selectRaw('avg(rating) as aggregate, member_id')
        ->groupBy('member_id');
    }

    public function getAvgRatingAttribute()
    {
        if ( ! array_key_exists('avgRating', $this->relations)) {
        $this->load('avgRating');
        }

        $relation = $this->getRelation('avgRating')->first();

        return ($relation) ? $relation->aggregate : null;
    }
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function city() {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function subdistrict() {
        return $this->belongsTo(Subdistrict::class, 'subdistrict_id');
    }
}
