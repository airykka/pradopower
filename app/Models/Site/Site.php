<?php

namespace App\Models\Site;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;
    protected $table = 'sites';
    protected $fillable = ['name', 'currency', 'phone_number'];
    protected $primaryKey = 'id';
    protected $keyType = 'string'; 
    public $incrementing = false;

    public function meters() {
        return $this->hasMany("App\Models\Meter\MeterSite", 'site_id');
    }

    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            $post->{$post->getKeyName()} = (string) \Str::uuid();
        });
    }
}
