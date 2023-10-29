<?php

namespace App\Models\Meter;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerMeter extends Model
{
    use HasFactory;

    protected $table = 'customer_meter';
    protected $fillable = ['meter_id', 'site_id', 'customer_id'];
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

    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function meter() {
        return $this->belongsTo(Meter::class, 'meter_id');
    }

    public function site() {
        return $this->belongsTo(Site::class, 'site_id');
    }
}
