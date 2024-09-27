<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CpuInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'brand',
        'arch',
        'bits',
        'cores',
        'threads',
        'frequency',
        'base_speed',
    ];
    public function device()
    {
        return $this->belongsTo(ComputerDevice::class, 'device_id');
    }
    public function cpuTemps()
    {
        return $this->hasMany(CpuTemp::class, 'cpu_id'); 
    }

    public function cpuUtilizations()
    {
        return $this->hasMany(CpuUtilization::class, 'cpu_id'); 
    }
}