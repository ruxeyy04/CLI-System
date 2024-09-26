<?php

namespace App\Http\Controllers;

use App\Events\GpuGraphUpdate;
use App\Events\PatchSaved;
use App\Models\GpuInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GpuController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string',
            'gpu_info.brand' => 'required|string',
            'gpu_info.temp' => 'required|numeric',
            'gpu_info.usage' => 'required|numeric',
            'gpu_info.memory' => 'required|numeric',
            'gpu_info.power' => 'required|numeric',
            'usage' => 'required|numeric',
            'temp' => 'required|numeric',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }
    
        $gpuInfoData = $request->input('gpu_info');
        $data = [
            'device_id' => $request->input('device_id'),
            'brand' => $gpuInfoData['brand'] ?? null,
            'temp' => $gpuInfoData['temp'] ?? null,
            'usage' => $gpuInfoData['usage'] ?? null,
            'memory' => $gpuInfoData['memory'] ?? null,
            'power' => $gpuInfoData['power'] ?? null
        ];
    
        $gpuInfo = GpuInfo::updateOrCreate(['device_id' => $data['device_id']], $data);
    
        $hasChanges = false;
        if ($gpuInfo->wasRecentlyCreated ||  $gpuInfo->wasChanged()) {
            
            $hasChanges = true;
        }
    
        $lastTemp = $gpuInfo->gpuTemps()->latest()->first();
        if ($request->has('temp')) {
            if (!$lastTemp || $lastTemp->temp !== $request->temp) {
                $gpuInfo->gpuTemps()->create(['temp' => $request->temp]);
                $gpuInfo->gpuUsage()->create(['usage' => $request->usage]);
                GpuGraphUpdate::dispatch($request->temp, $request->usage, $request->input('device_id'));
            }
        }
    
        // Handle usage data
        $lastUsage = $gpuInfo->gpuUsage()->latest()->first();
        if ($request->has('usage')) {
            if (!$lastUsage || $lastUsage->usage !== $request->usage) {
                $gpuInfo->gpuTemps()->create(['temp' => $request->temp]);
                $gpuInfo->gpuUsage()->create(['usage' => $request->usage]);
                GpuGraphUpdate::dispatch($request->temp, $request->usage, $request->input('device_id'));
            }
        }
    
        if ($hasChanges) {
            PatchSaved::dispatch($request->input('device_id'));
        }
    
        return response()->json(['success' => true, 'data' => $request->all()]);
    }
    
}
