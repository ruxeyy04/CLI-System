<?php

namespace App\Http\Controllers;

use App\Events\DeviceSaved;
use App\Events\PatchSaved;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function verify(Request $request)
    {

        $deviceData = $request->all();

   
        PatchSaved::dispatch('1D6BA1C3-F233-DC2C-F6DC-5811224D4468');

        return response()->json(['success' => true, 'data' => $deviceData]);
    }
}
