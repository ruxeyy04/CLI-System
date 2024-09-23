<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboratory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'laboratory_name',
        'capacity',
    ];

    /**
     * Get the users assigned to the laboratory.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function computerDevices()
    {
        return $this->hasMany(ComputerDevice::class);
    }
}
