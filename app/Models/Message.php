<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = ['owner_id', 'message', 'status', 'status_note', 'type'];

    public function customer() {
        return $this->belongsTo(Customer::class, 'id');
    }

    public function agent() {
        return $this->belongsTo(Agent::class, 'id');
    }

    public function meter() {
        return $this->belongsTo('App\Meter\Meter', 'id');
    }
}
