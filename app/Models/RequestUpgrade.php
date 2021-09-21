<?php

namespace App\Models;

use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequestUpgrade extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
