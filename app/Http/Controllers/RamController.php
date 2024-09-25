<?php

namespace App\Http\Controllers;

use App\Events\PatchSaved;
use App\Events\RamGraphUpdate;
use App\Models\RamInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RamController extends Controller
{
    public function store(Request $request)
    {
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
        if ($ramInfo->wasRecentlyCreated ||  $ramInfo->wasChanged()) {

            $hasChanges = true;
        }
        $lastUsage = $ramInfo->ramUsage()->latest()->first();
        if ($request->has('usage')) {

            if (!$lastUsage || $lastUsage->usage !== $request->usage) {
                $ramInfo->ramUsage()->create(['usage' => $request->usage]);
                RamGraphUpdate::dispatch($request->usage, $request->input('device_id'));
            }
        }
        if ($hasChanges) {
            PatchSaved::dispatch($request->input('device_id'));
        }
       
        return response()->json(['success' => true, 'data' => $request->all()]);
    }
}
