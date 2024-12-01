<?php

namespace App\Http\Controllers;

use App\Events\CpuGraphUpdate;
use App\Events\NotificationAlert;
use App\Events\PatchSaved;
use App\Models\CpuInfo;
use App\Models\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CpuController extends Controller
{
    public function store(Request $request)
    {
        $createNotification = false;
        $notificationTitle = '';
        $notificationMessage = '';
        // $validator = Validator::make($request->all(), [
        //     'device_id' => 'required|string',
        //     'cpu_info.brand' => 'string',
        //     'cpu_info.arch' => 'string',
        //     'cpu_info.bits' => 'string',
        //     'cpu_info.cores' => 'string',
        //     'cpu_info.threads' => 'string',
        //     'cpu_info.frequency' => 'string',
        //     'cpu_info.base_speed' => 'string',
        //     'temp' => 'required|string',
        //     'util' => 'required|string',
        // ]);
        
        // if ($validator->fails()) {
        //     return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        // }

        $cpuInfoData = $request->input('cpu_info');
        $data = [
            'device_id' => $request->input('device_id'),
            'brand' => $cpuInfoData['brand'] ?? null,
            'arch' => $cpuInfoData['arch'] ?? null,
            'bits' => $cpuInfoData['bits'] ?? null,
            'cores' => $cpuInfoData['cores'] ?? null,
            'threads' => $cpuInfoData['threads'] ?? null,
            'frequency' => $cpuInfoData['frequency'] ?? null,
            'base_speed' => $cpuInfoData['base_speed'] ?? null,
        ];

        $cpuInfo = CpuInfo::updateOrCreate(['device_id' => $data['device_id']], $data);
        $hasChanges = false;

        if ($cpuInfo->wasRecentlyCreated || $cpuInfo->wasChanged()) {
            $hasChanges = true;
        }

        $lastTemp = $cpuInfo->cpuTemps()->latest()->first();
        $lastUtilization = $cpuInfo->cpuUtilizations()->latest()->first();

        // Current values
        $currentTemp = $request->input('temp');
        $currentUtil = $request->input('util');

        // Checking if the notification has already been sent for the current temp/util
        $lastTempAlert = $lastTemp && $lastTemp->temp >= 80;
        $lastUtilAlert = $lastUtilization && $lastUtilization->util >= 90;

        // CPU Temperature Alert
        if ($currentTemp >= 80 && !$lastTempAlert) {
            $createNotification = true;
            $notificationTitle = 'CPU Temperature Alert';
            $notificationMessage = "CPU temperature has reached {$currentTemp}°C, which is above the threshold of 80°C.";
        }

        // CPU Utilization Alert
        if ($currentUtil >= 90 && !$lastUtilAlert) {
            $createNotification = true;
            $notificationTitle = 'CPU Utilization Alert';
            $notificationMessage = "CPU utilization has reached {$currentUtil}%, which is above the threshold of 90%.";
        }

        // Create or update temperature and utilization data
        $cpuInfo->cpuTemps()->create(['temp' => $currentTemp]);
        CpuGraphUpdate::dispatch($currentTemp, $currentUtil, $request->input('device_id'));

        $cpuInfo->cpuUtilizations()->create(['util' => $currentUtil]);
        CpuGraphUpdate::dispatch($currentTemp, $currentUtil, $request->input('device_id'));

        if ($hasChanges) {
            PatchSaved::dispatch($request->input('device_id'));
        }

        // Send notification if needed
        if ($createNotification) {
            Notifications::create([
                'title' => $notificationTitle,
                'message' => $notificationMessage,
                'type' => 'CPU',
                'is_read' => false,
                'device_id' => $request->input('device_id'),
            ]);
            NotificationAlert::dispatch($request->input('device_id'), $notificationMessage, $notificationTitle);
        }

        return response()->json(['success' => true, 'data' => $request->all()]);
    }
}
