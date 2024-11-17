<?php

namespace App\Http\Controllers;

use App\Events\DiskUpdate;
use App\Events\NotificationAlert;
use App\Models\DiskInfo;
use App\Models\Notifications;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DiskController extends Controller
{
    public function store(Request $request)
    {
        try {
            $device_id = $request->device_id;  
            $providedSerialNumbers = array_keys($request->disk_list); 
    
            $existingDisks = DiskInfo::where('device_id', $device_id)->get();
            $changesMade = false; // Flag to track changes
            $createNotification = false;
            $notificationTitle = '';
            $notificationMessage = '';

            foreach ($request->disk_list as $serial_number => $disk) {
                $diskInfo = DiskInfo::updateOrCreate(
                    [
                        'device_id' => $device_id,
                        'serial_number' => $serial_number
                    ],
                    [
                        'volume_label' => $disk['volume_label'],
                        'mountpoint' => $disk['mountpoint'],
                        'total' => $disk['total'],
                        'used' => $disk['used'],
                        'free' => $disk['free'],
                        'temperature' => $disk['temperature'],
                        'health' => $disk['health'],
                        'drive_type' => $disk['drive_type'],
                        'model' => $disk['model'],
                        'status' => 'active',
                        'ejected_on' => null,
                    ]
                );
    
                // Calculate the storage usage percentage
                $usagePercentage = round(($disk['used'] / $disk['total']) * 100);
    
                // Check if the usage percentage exceeds 90% and create a notification if needed
                $lastNotification = Notifications::where('device_id', $device_id)
                                                  ->where('type', 'Storage')
                                                  ->where('is_read', false)
                                                  ->where('message', 'LIKE', "%{$usagePercentage}%")
                                                  ->first();
    
                if ($usagePercentage > 90 && !$lastNotification) {
                    $createNotification = true;
                    $notificationTitle = 'Disk Usage Alert';
                    $notificationMessage = "The disk mounted at {$disk['mountpoint']} has reached {$usagePercentage}%, which is above the threshold of 90%.";
                }

                // Check if a new record was created or an existing one was updated
                if ($diskInfo->wasRecentlyCreated || $diskInfo->wasChanged()) {
                    $changesMade = true;
                }
            }
    
            foreach ($existingDisks as $existingDisk) {
                // Only update if the disk is currently active
                if (!in_array($existingDisk->serial_number, $providedSerialNumbers) && $existingDisk->status !== 'inactive') {
                    $existingDisk->update([
                        'status' => 'inactive',
                        'ejected_on' => Carbon::now(),
                    ]);
                    $changesMade = true; // Mark that a change was made
                }
            }
    
            // Dispatch the event only if changes were made
            if ($changesMade) {
                DiskUpdate::dispatch($device_id);
            }
    
            // Create notification if needed
            if ($createNotification) {
                Notifications::create([
                    'title' => $notificationTitle,
                    'message' => $notificationMessage,
                    'type' => 'Storage',
                    'is_read' => false,
                    'device_id' => $device_id,
                ]);
                NotificationAlert::dispatch($device_id, $notificationMessage, $notificationTitle);
            }
    
            return response()->json(['success' => true, 'message' => 'Disk data updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function get(Request $request)
    {
        $validated = $request->validate([
            'device_id' => 'required',
        ]);

        $deviceId = $validated['device_id'];

        $diskInfos = DiskInfo::where('device_id', $deviceId)->whereNull('ejected_on')->get();

        if ($diskInfos->isEmpty()) {
            return response()->json(['message' => 'No disk information found for the specified device_id.'], 404);
        }

        $response = $diskInfos->map(function ($diskInfo) {
            return [
                'volume_label' => $diskInfo->volume_label,
                'mountpoint' => $diskInfo->mountpoint,
                'total' => $diskInfo->total,
                'used' => $diskInfo->used,
                'free' => $diskInfo->free,
                'health' => $diskInfo->health,
                'temperature' => $diskInfo->temperature,
                'drive_type' => $diskInfo->drive_type,
                'model' => $diskInfo->model,
                'serial_number' => $diskInfo->serial_number,
                'status' => $diskInfo->status,
                'ejected_on' => $diskInfo->ejected_on ? $diskInfo->ejected_on->format('Y-m-d H:i:s') : null,
            ];
        });

        return response()->json($response);
    }
}
