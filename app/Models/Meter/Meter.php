<?php

namespace App\Models\Meter;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Customer;
use App\Models\Meter\MeterSite;
use App\Models\Meter\Purchase;

class Meter extends Model
{
    use HasFactory;

    protected $table = 'meters';
    protected $fillable = ['meter_number', 'site_id', 'status'];
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    public function site() {
        return $this->belongsTo(MeterSite::class, 'id');
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'id');
    }

    public function purchases() {
        return $this->hasMany(Purchase::class, 'meter_number', 'meter_number');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            $post->{$post->getKeyName()} = (string) \Str::uuid();
        });
    }
}
