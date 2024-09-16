<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/session-auth-info', function () {
    $user = Auth::user();

    return response()->json([
        'user' => $user,
        'email_verified' => $user ? !is_null($user->email_verified_at) : null,
        'session' => Session::all(),
    ]);
});

Route::view('/', 'welcome')->middleware(['guest']);
Route::view('/test', 'test');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
