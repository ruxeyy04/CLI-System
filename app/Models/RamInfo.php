<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RamInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'total_ram',
        'used',
        'available',
        'speed'
    ];
    public function ramUsage()
    {
        return $this->hasMany(RamUsage::class, 'ram_id'); 
    }
}
