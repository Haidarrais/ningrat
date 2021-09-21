<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'upper',
        'api_token',
        'last_upgrade',
        'kode_referal',
        'status',
        'add_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['total_point'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot() {
        parent::boot();
        static::creating(function ($query) {
            $query->api_token = Str::random(10);
            $query->kode_referal = Str::random(10);
            $query->last_upgrade = Carbon::now();
        });
    }

    // Hash secara default (Jadi tidak perlu hash di controller)
    public function setPasswordAttribute($value) {
        $this->attributes['password'] = Hash::make($value);
    }

    public function hirarki() {
        return $this->hasMany(User::class, 'upper');
    }

    public function scopeFindReferal($query, $kode_referal) {
        return $query->where('kode_referal', $kode_referal);
    }

    public function scopeFindToken($query, $api_token) {
        return $query->where('api_token', $api_token)->first();
    }

    public function member() {
        return $this->hasOne(Member::class);
    }

    public function order() {
        return $this->hasMany(Order::class);
    }

    public function transaction() {
        return $this->hasMany(Transaction::class);
    }

    public function isCustomer(){
       return $this->hasRole('customer');
    }

    public function isReseller(){
        return $this->hasRole('reseller');
     }

    public function request_upgrade() {
        return $this->hasMany(RequestUpgrade::class);
    }

    public function stock() {
        return $this->hasMany(Stock::class);
    }

    public function point() {
    	return $this->belongsToMany(Point::class)->withPivot('total', 'created_at');
    }

    public function reward() {
        return $this->belongsToMany(Reward::class)->withPivot('status', 'created_at', 'updated_at', 'id');
    }

    public function getTotalPointAttribute() {
        $total = ($this->point()->sum('total') != null) ? $this->point()->sum('total') : 0;
        // $reward = $this->reward()->where('rewards.status', 1)->get();
        $reward = $this->reward()->get();
        $total_reward = 0;
        foreach ($reward as $key => $value) {
            if($value->pivot->status == 1) $total_reward += $value->point;
        }
        $subtotal = $total - $total_reward;
        return $subtotal < 0 ? 0 : $subtotal;
    }
}
