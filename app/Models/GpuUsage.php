<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GpuUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'gpu_id',
        'usage',
    ];

    public function gpuInfo()
    {
        return $this->belongsTo(GpuInfo::class, 'gpu_id'); 
    }
}
