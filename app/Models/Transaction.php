<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = "transactions";
    protected $fillable = [
        "ref",
        "user_id",
        "amount",
        "description",
        "status",
        "type",
        "sub_type",
        "beneficiary"
    ];

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;


    protected static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            $post->{$post->getKeyName()} = (string) \Str::uuid();
        });
    }

    public function user() {
        return $this->belongsTo(Customer::class, 'id');
    }

    public function agent() {
        return $this->belongsTo(Agent::class, 'id');
    }
}
