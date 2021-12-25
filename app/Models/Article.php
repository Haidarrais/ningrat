<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    protected $fillable = [
        'articles',
        'banner_id'
    ];
    public function banner(){
        return $this->belongsTo(Content::class, 'banner_id');
    }
}
