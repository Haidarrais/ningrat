<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = ["parent", "key", "value", "role", "minimal_transaction", "discount"];
    protected $appends = ['new_key'];

    public static function checkData($key, $role) {
        if ($role) {
            return Setting::where('key', $key)->where('role',$role)->first();
        }else{
            return Setting::where('key', $key)->first();
        }
    }

    public function getNewKeyAttribute() {
        return Str::title(str_replace('-', ' ', $this->key));
    }
}
