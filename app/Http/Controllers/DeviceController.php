<?php

namespace App\Http\Controllers;

use App\Events\DeviceSaved;
use App\Events\PatchSaved;
use App\Models\ComputerDevice;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function verify(Request $request)
    {
        $deviceid = $request->device_id;
        $patchid = $request->patch_id;
        $token = $request->token;

        if (is_null($deviceid)) {
            return response()->json(['success' => false, 'message' => 'Device ID is required.']);
        }
        $device = ComputerDevice::find($deviceid);

        if (!$device) {
            return response()->json(['success' => false, 'message' => 'Device ID not added yet.']);
        }
        if ($device->patch_id !== null) {
            return response()->json(['success' => false, 'message' => 'Device ID is patched already']);
        } else {
            $device->update([
                'patch_id' => $patchid,
                'token' => $token,
                'patched_date' => now(),
            ]);

            PatchSaved::dispatch($deviceid);
            return response()->json(['success' => true, 'data' => "Patched Successfully"]);
        }
    }
}
