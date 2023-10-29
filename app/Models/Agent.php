<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Agent extends Authenticatable implements JWTSubject
{
    use HasFactory;
    protected $fillable = [
        'first_name','last_name','phone_number','site','credit_balance', 'email',
        'status','language','account_ref','is_credit_limited','credit_threshold','password'
    ]; 
    
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    public function wallet() {
        return $this->hasOne(Wallet::class, 'id', 'user_id');
    }

    public function transactions() {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    public function tickets() {
        return $this->hasMany(Support::class, 'user_id');
    }

    public function site() {
        return $this->hasMany(Site::class, 'id');
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
