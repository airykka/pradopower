<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportComment extends Model
{
    use HasFactory;

    protected $table = 'ticket_comments';
    protected $fillable = ['ticket_id', 'message', 'attachment'];

    public function ticket() {
        return $this->belongsTo(Support::class, 'ticket_id');
    }
}
