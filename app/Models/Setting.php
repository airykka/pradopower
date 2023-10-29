<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Config;
    
class Setting extends Model   {
    
    use HasFactory;
    
    /**
     * @var string
     */
    protected $table = 'settings';

    /**
     * @var array
     */
    protected $fillable = ['key', 'value'];
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
    
    /**
     * @param $key
     */
    public static function get($key)
    {
        $setting = new self();
        $entry = $setting->where('key', $key)->first();
        if (!$entry) {
            return;
        }
        return $entry->value;
    }

    /**
     * @param $key
     * @param null $value
     * @return bool
     */
    public static function set($key, $value = null)
    {
        $setting = new self();
        $entry = $setting->where('key', $key)->first();
        if($entry) {
            $entry->value = $value;
            $entry->save();
            Config::set('key', $value);
            if (Config::get($key) == $value) {
                return true;
            }            
        } else {
            $setting->key = $key;
            $setting->value = $value;
            $setting->save();
            Config::set('key', $value);
            if (Config::get($key) == $value) {
                return true;
            } 
        }
        return false;
    }
}
