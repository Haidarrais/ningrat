<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = ["parent", "key", "value"];
    protected $appends = ['new_key'];

    public function scopeFindKey($query, $key) {
        return $query->where('key', $key)->first();
    }

    public function getNewKeyAttribute() {
        return Str::title(str_replace('-', ' ', $this->key));
    }
}
