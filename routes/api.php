<?php

use App\Events\NumberPosted;
use App\Http\Controllers\CpuController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\GpuController;
use App\Http\Controllers\RamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/post-number', function (Request $request) {
    $number = $request->input('number');
    $word = $request->input('word');
    NumberPosted::dispatch($number, $word);
    
    return response()->json(['message' => 'Number received and broadcasted', 'number' => $number]);
});

Route::put('/device', [DeviceController::class, 'verify']);

Route::post('/cpu', [CpuController::class, 'store']);
Route::post('/ram', [RamController::class, 'store']);
Route::post('/gpu', [GpuController::class, 'store']);