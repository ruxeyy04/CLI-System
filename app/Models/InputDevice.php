<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InputDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'brand',
        'model',
        'serial_number',
        'manufacturer',
        'description',
        'input_id',
        'input_status',
        'physical_status',
        'note',
        'note_added',
        'removed_on',
        'creation_class_name',
        'device_type',

    ];

    protected $casts = [
        'note_added' => 'datetime', 
        'removed_on' => 'datetime'
    ];
    public function device()
    {
        return $this->belongsTo(ComputerDevice::class, 'device_id');
    }
}
