<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CpuTemp extends Model
{
    use HasFactory;

    protected $fillable = [
        'cpu_id',
        'temp',
    ];

    public function cpuInfo()
    {
        return $this->belongsTo(CpuInfo::class, 'cpu_id'); 
    }
}