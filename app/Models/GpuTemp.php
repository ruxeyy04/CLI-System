<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GpuTemp extends Model
{
    use HasFactory;

    protected $fillable = [
        'gpu_id',
        'temp',
    ];

    public function gpuInfo()
    {
        return $this->belongsTo(GpuInfo::class, 'gpu_id'); 
    }
}
