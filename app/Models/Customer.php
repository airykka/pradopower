<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Meter\Purchase;
use App\Models\Meter\Meter;

class Customer extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'first_name','last_name','phone_number','site','account_balance',
        'account_ref','status','language', 'meter_id', 'site_id', 'email','password',
        'is_credit_limited','credit_threshold','credit_balance','user_type'
    ];
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    public function meter() {
        return $this->hasOne(Meter::class, 'id', 'meter_id');
    }

    public function site() {
        return $this->hasOne(Site::class, 'id', 'user_id');
    } 

    public function transactions() {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    public function wallet() {
        return $this->hasOne(Wallet::class, 'user_id');
    }

    public function tickets() {
        return $this->hasMany(Support::class, 'user_id');
    }

    public function purchases() {
        return $this->hasMany(Purchase::class, 'meter_number');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            $post->{$post->getKeyName()} = (string) \Str::uuid();
        });
    }

    protected $hidden = [
        'password'
    ];

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }
}
