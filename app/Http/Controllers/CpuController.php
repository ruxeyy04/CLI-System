<?php

namespace App\Http\Controllers;

use App\Events\CpuGraphUpdate;
use App\Events\PatchSaved;
use App\Models\CpuInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CpuController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string',
            'cpu_info.brand' => 'string',
            'cpu_info.arch' => 'string',
            'cpu_info.bits' => 'string',
            'cpu_info.cores' => 'string',
            'cpu_info.threads' => 'string',
            'cpu_info.frequency' => 'string',
            'cpu_info.base_speed' => 'string',
            'temp' => 'required|string',
            'util' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

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
        if ($cpuInfo->wasRecentlyCreated ||  $cpuInfo->wasChanged()) {
            
            $hasChanges = true;
        }
        $lastTemp = $cpuInfo->cpuTemps()->latest()->first();
        if ($request->has('temp')) {

            if (!$lastTemp || $lastTemp->temp !== $request->temp) {
                $cpuInfo->cpuTemps()->create(['temp' => $request->temp]);
                $cpuInfo->cpuUtilizations()->create(['util' => $request->util]);
                CpuGraphUpdate::dispatch($request->temp, $request->util, $request->input('device_id'));
            }
        }
        $lastUtilization = $cpuInfo->cpuUtilizations()->latest()->first();
        if ($request->has('util')) {

            if (!$lastUtilization || $lastUtilization->util !== $request->util) {
                $cpuInfo->cpuUtilizations()->create(['util' => $request->util]);
                $cpuInfo->cpuTemps()->create(['temp' => $request->temp]);
                CpuGraphUpdate::dispatch($request->temp, $request->util, $request->input('device_id'));
            }
        }
        if ($hasChanges) {
            PatchSaved::dispatch($request->input('device_id'));
        }
        return response()->json(['success' => true, 'data' => $request->all()]);
    }
}
