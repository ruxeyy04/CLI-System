<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiskInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'volume_label',
        'mountpoint',
        'total',
        'used',
        'free',
        'health',
        'temperature',
        'drive_type',
        'model',
        'serial_number',
        'status',
        'ejected_on'
    ];

    protected $casts = [
        'ejected_on' => 'datetime', 
    ];
}
