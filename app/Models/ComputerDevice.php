<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComputerDevice extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'device_name',
        'serial_number',
        'patch_id',
        'token',
        'laboratory_id',
        'patched_date',
    ];
    protected $casts = [
        'patched_date' => 'datetime', // This will ensure it's treated as a Carbon instance
    ];
    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }
}
