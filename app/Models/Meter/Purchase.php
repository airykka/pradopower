<?php

namespace App\Models\Meter;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $table = 'purchases';
    protected $fillable = [
        'total_paid',
        'total_unit',
        'token',
        'customer_number',
        'customer_name',
        'customer_addr',
        'meter_number',
        'gen_datetime',
        'gen_user',
        'company',
        'price',
        'vat',
        'tid_datetime',
        'currency',
        'unit',
        'TaskNo',
        'status'
    ];

    public function meter() {
        return $this->belongsTo('\App\Models\Meter\Meter', 'meter_number');
    }

    public function customer() {
        return $this->belongsTo('App\Models\Customer', 'meter_id');
    }
}
