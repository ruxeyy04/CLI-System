<?php

namespace App\Http\Controllers;

use App\Events\InputDevicesUpdate;
use App\Models\InputDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class InputDeviceController extends Controller
{
    public function store(Request $request)
    {
        try {
            $deviceId = $request->device_id;  
            $deviceTypes = ['keyboard', 'pointing_device']; 
            $changesMade = false; 
    
            $providedInputIds = [];
    
            foreach ($deviceTypes as $deviceType) {
                if ($request->has($deviceType)) {
                    foreach ($request->input($deviceType) as $inputId => $deviceData) {
                        $providedInputIds[] = $inputId;
                        $inputDevice = InputDevice::updateOrCreate(
                            [
                                'device_id' => $deviceId,
                                'input_id' => $inputId
                            ],
                            [
                                'device_type' => $deviceType,
                                'description' => $deviceData['Description'],
                                'input_status' => $deviceData['Status'],
                                'creation_class_name' => $deviceData['CreationClassName'],
                                'removed_on' => null 
                            ]
                        );
    
                        if ($inputDevice->wasRecentlyCreated || $inputDevice->wasChanged()) {
                            $changesMade = true;
                        }
                    }
                }
            }
    
            $existingDevices = InputDevice::where('device_id', $deviceId)->get();
    
            foreach ($existingDevices as $existingDevice) {
                if (!in_array($existingDevice->input_id, $providedInputIds) && $existingDevice->removed_on === null) {
                    $existingDevice->update([
                        'removed_on' => Carbon::now(),
                    ]);
                    $changesMade = true; 
                }
            }
    
            if ($changesMade) {
                InputDevicesUpdate::dispatch($deviceId);
                return response()->json(['success' => true, 'message' => 'Input device data updated successfully']);
            } else {
                return response()->json(['success' => true, 'message' => 'No changes were made']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
}
