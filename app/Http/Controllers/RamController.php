<?php

namespace App\Http\Controllers;

use App\Events\NotificationAlert;
use App\Events\PatchSaved;
use App\Events\RamGraphUpdate;
use App\Models\RamInfo;
use App\Models\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RamController extends Controller
{
    public function store(Request $request)
    {
        $createNotification = false;
        $notificationTitle = '';
        $notificationMessage = '';

        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string',
            'ram_info.total_ram' => 'required|numeric',
            'ram_info.used' => 'required|numeric',
            'ram_info.available' => 'required|numeric',
            'ram_info.speed' => 'required|numeric',
            'usage' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $ramInfoData = $request->input('ram_info');
        $data = [
            'device_id' => $request->input('device_id'),
            'total_ram' => $ramInfoData['total_ram'] ?? null,
            'used' => $ramInfoData['used'] ?? null,
            'available' => $ramInfoData['available'] ?? null,
            'speed' => $ramInfoData['speed'] ?? null,
        ];

        $ramInfo = RamInfo::updateOrCreate(['device_id' => $data['device_id']], $data);
        $hasChanges = false;

        if ($ramInfo->wasRecentlyCreated || $ramInfo->wasChanged()) {
            $hasChanges = true;
        }

        $lastUsage = $ramInfo->ramUsage()->latest()->first();

        // Check if the RAM usage exceeds 85% and send a notification if needed
        $lastUsageAlert = $lastUsage && $lastUsage->usage >= 85;

        if ($request->has('usage')) {
            if (!$lastUsage || $lastUsage->usage !== $request->usage) {
                $ramInfo->ramUsage()->create(['usage' => $request->usage]);
                RamGraphUpdate::dispatch($request->usage, $request->input('device_id'));

                if ($request->usage >= 85 && !$lastUsageAlert) {
                    $createNotification = true;
                    $notificationTitle = 'RAM Usage Alert';
                    $notificationMessage = "RAM usage has reached {$request->usage}%, which is above the threshold of 85%.";
                }
            }
        }

        // Trigger PatchSaved event if there are changes
        if ($hasChanges) {
            PatchSaved::dispatch($request->input('device_id'));
        }

        // Create notification if needed
        if ($createNotification) {
            Notifications::create([
                'title' => $notificationTitle,
                'message' => $notificationMessage,
                'type' => 'RAM',
                'is_read' => false,
                'device_id' => $request->input('device_id'),
            ]);
            NotificationAlert::dispatch($request->input('device_id'), $notificationMessage, $notificationTitle);
        }

        return response()->json(['success' => true, 'data' => $request->all()]);
    }
}
