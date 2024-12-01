<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RamUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'ram_id',
        'usage',
    ];

    public function ramInfo()
    {
        return $this->belongsTo(RamInfo::class, 'ram_id');
    }
    
}
