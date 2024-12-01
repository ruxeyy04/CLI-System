<?php

namespace App\Http\Controllers;

use App\Models\ComputerDevice;
use Illuminate\Http\Request;

class DeviceLogs extends Controller
{
    public function show($id)
    {
        $device = ComputerDevice::findOrFail($id);
        return view('devicelogs', compact('device'));
    }
    
}
