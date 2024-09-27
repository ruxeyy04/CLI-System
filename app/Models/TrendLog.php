<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrendLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'type',
        'component',
        'start_datetime',
        'end_datetime',
        'description',
    ];
    protected $casts = [
        'start_datetime' => 'datetime', 
        'end_datetime' => 'datetime', 
    ];
    public function device()
    {
        return $this->belongsTo(ComputerDevice::class, 'device_id');
    }
}
