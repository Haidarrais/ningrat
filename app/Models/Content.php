<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'content_type',
        'image'
    ];

    public function article(){
        return $this->hasOne(Article::class, 'banner_id', 'id');
    }
}
