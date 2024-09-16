<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Livewire\Volt\Volt;

Route::get('/session-auth-info', function () {
    $user = Auth::user();

    return response()->json([
        'user' => $user,
        'email_verified' => $user ? !is_null($user->email_verified_at) : null,
        'session' => Session::all(),
    ]);
});

Route::middleware(['auth', 'verified'])->group(function () {

    Volt::route('dashboard', 'pages.dashboard')->name('dashboard');
    Volt::route('editprofile','pages.editprofile')->name('editprofile');

    Volt::route('userprofile', 'pages.userprofile')->name('userprofile');
});
Route::view('/', 'welcome')->middleware(['guest']);
Route::view('/test', 'test');



require __DIR__ . '/auth.php';
