<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use App\Models\ComputerDevice;
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('user-session.{sessionId}', function ($user, $sessionId) {
    $session = DB::table('sessions')->where('id', $sessionId)->first();

    return $session && $session->user_id === $user->id;
});

Broadcast::channel('cpu-graph.{deviceId}', function ($user, $deviceId) {
    $deviceExists = ComputerDevice::where('id', $deviceId)->exists();

    return $deviceExists;
});

Broadcast::channel('ram-graph.{deviceId}', function ($user, $deviceId) {
    $deviceExists = ComputerDevice::where('id', $deviceId)->exists();

    return $deviceExists;
});

Broadcast::channel('gpu-graph.{deviceId}', function ($user, $deviceId) {
    $deviceExists = ComputerDevice::where('id', $deviceId)->exists();

    return $deviceExists;
});

Broadcast::channel('disk-update.{deviceId}', function ($user, $deviceId) {
    $deviceExists = ComputerDevice::where('id', $deviceId)->exists();

    return $deviceExists;
});