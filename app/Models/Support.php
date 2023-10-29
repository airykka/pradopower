<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    use HasFactory;

    protected $table = 'tickets';
    protected $fillable = ['user_id', 'title', 'message', 'status', 'active_status', 'attachment'];

    public function comments() {
        return $this->hasMany(SupportComment::class, 'ticket_id');
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'user_id');
    }

    public function agent() {
        return $this->belongsTo(Agent::class, 'user_id');
    }
}
