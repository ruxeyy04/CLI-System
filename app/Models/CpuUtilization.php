<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CpuUtilization extends Model
{
    use HasFactory;

    protected $fillable = [
        'cpu_id',
        'util',
    ];

    public function cpuInfo()
    {
        return $this->belongsTo(CpuInfo::class, 'cpu_id');
    }
}