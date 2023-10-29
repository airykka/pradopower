<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;
    protected $table = 'wallets';
    protected $fillable = [
        'user_id',
        'balance',
        'amount',
        'prev_balance',
        'account_ref',
        'account_number',
        'currency',
        'bank_name',
        'bank_code',
        'status'
    ];
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    public function customer() {
        return $this->belongsTo(Customer::class, 'user_id');
    }

    public function agent() {
        return $this->belongsTo(Agent::class, 'id');
    }

    public function owner() {
        return $this->belongsTo(Agent::class, 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            $post->{$post->getKeyName()} = (string) \Str::uuid();
        });
    }
}
