<?php

namespace App\Models\Meter;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeterToken extends Model
{
    use HasFactory;
    protected $table = 'meter_token';
    protected $fillable = [ 'meterNo', 'token', 'status', 'taskNo', 'CreateDate', 'EndDate', 'tokenStatus' ];
}
