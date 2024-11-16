<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'title',
        'message',
        'type',
        'is_read',
        'device_id',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function device()
    {
        return $this->belongsTo(ComputerDevice::class, 'device_id');
    }
}
