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
            'cpu_info.bits' => 'integer',
            'cpu_info.cores' => 'integer',
            'cpu_info.threads' => 'integer',
            'cpu_info.frequency' => 'numeric',
            'cpu_info.base_speed' => 'numeric',
            'temp' => 'required|numeric',
            'util' => 'required|numeric',
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

        $lastTemp = $cpuInfo->cpuTemps()->latest()->first();
        if ($request->has('temp')) {

            if (!$lastTemp || $lastTemp->temp !== $request->temp) {
                $cpuInfo->cpuTemps()->create(['temp' => $request->temp]);
                // Assuming you're dispatching single values
                CpuGraphUpdate::dispatch([$request->temp], [$request->util], $request->input('device_id'));
            }
        }
        $lastUtilization = $cpuInfo->cpuUtilizations()->latest()->first();
        if ($request->has('util')) {

            if (!$lastUtilization || $lastUtilization->util !== $request->util) {
                $cpuInfo->cpuUtilizations()->create(['util' => $request->util]);
                // Assuming you're dispatching single values
                CpuGraphUpdate::dispatch($request->temp, $request->util, $request->input('device_id'));
            }
        }
        CpuGraphUpdate::dispatch($request->temp, $request->util, $request->input('device_id'));
        PatchSaved::dispatch($request->input('device_id'));
        return response()->json(['success' => true, 'data' => $request->all()]);
    }
}
