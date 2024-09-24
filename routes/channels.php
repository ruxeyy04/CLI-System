<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\DB;
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});


Broadcast::channel('user-session.{sessionId}', function ($user, $sessionId) {
    $session = DB::table('sessions')->where('id', $sessionId)->first();

    return $session && $session->user_id === $user->id;
});

