<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GpuInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'brand',
        'temp',
        'usage',
        'memory',
        'power'
    ];

    public function gpuTemps()
    {
        return $this->hasMany(GpuTemp::class, 'gpu_id'); 
    }

    public function gpuUsage()
    {
        return $this->hasMany(GpuUsage::class, 'gpu_id'); 
    }
    public function device()
    {
        return $this->belongsTo(ComputerDevice::class, 'device_id');
    }
}
