<?php

namespace App\Http\Controllers;

use App\Events\InputDevicesUpdate;
use App\Events\NotificationAlert;
use App\Models\InputDevice;
use App\Models\Notifications;
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
            $newNotifications = [];

            $providedInputIds = [];

            foreach ($deviceTypes as $deviceType) {
                if ($request->has($deviceType)) {
                    foreach ($request->input($deviceType) as $inputId => $deviceData) {
                        $providedInputIds[] = $inputId;

                        // Handle creating or updating device info
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

                        // Check if it's a new record or updated
                        if ($inputDevice->wasRecentlyCreated) {
                            $changesMade = true;
                            // Prepare a notification based on device type
                            $message = $deviceType == 'keyboard' 
                                ? "A new keyboard with description '{$deviceData['Description']}' has been added."
                                : "A new pointing device with description '{$deviceData['Description']}' has been added.";
                            $newNotifications[] = $message;
                        } elseif ($inputDevice->wasChanged()) {
                            // Handle updates (for example, a keyboard removed)
                            if ($deviceType == 'keyboard') {
                                $newNotifications[] = "A keyboard has been removed.";
                            }
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

                    // Prepare removal notification based on device type
                    $message = $existingDevice->device_type == 'keyboard' 
                        ? "A keyboard has been removed."
                        : "A pointing device has been removed.";
                    $newNotifications[] = $message;
                }
            }

            // If changes were made, dispatch the update event and create notifications
            if ($changesMade) {
                // Dispatch the event
                InputDevicesUpdate::dispatch($deviceId);

                // Create notifications for new devices or removals
                foreach ($newNotifications as $notificationMessage) {
                    Notifications::create([
                        'title' => 'Input Device Update',
                        'message' => $notificationMessage,
                        'type' => 'Input Device',
                        'is_read' => false,
                        'device_id' => $deviceId,
                    ]);
                    // Dispatch a notification alert
                    NotificationAlert::dispatch($deviceId, $notificationMessage, "Input Device Update");
                }

                return response()->json(['success' => true, 'message' => 'Input device data updated successfully']);
            } else {
                return response()->json(['success' => true, 'message' => 'No changes were made']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
    public function get(Request $request)
    {
        $deviceId = $request->input('device_id');

        $inputDevices = InputDevice::where('device_id', $deviceId)->whereNull('removed_on')->get();

        $groupedDevices = $inputDevices->groupBy('device_type');

        $response = [];

        foreach ($groupedDevices as $deviceType => $devices) {
            $response[$deviceType] = $devices->map(function ($device) {
                return [
                    'brand' => $device->brand,
                    'model' => $device->model,
                    'serial_number' => $device->serial_number,
                    'manufacturer' => $device->manufacturer,
                    'description' => $device->description,
                    'input_id' => $device->input_id,
                    'input_status' => $device->input_status,
                    'physical_status' => $device->physical_status,
                    'note' => $device->note,
                    'note_added' => $device->note_added ? $device->note_added->format('Y-m-d H:i:s') : null,
                    'removed_on' => $device->removed_on ? $device->removed_on->format('Y-m-d H:i:s') : null,
                    'creation_class_name' => $device->creation_class_name,
                ];
            });
        }

        return response()->json($response);
    }
}
