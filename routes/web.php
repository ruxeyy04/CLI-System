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
});

Route::middleware(['auth'])
    ->prefix('account')
    ->group(function () {
        Volt::route('settings', 'pages.profile.settings')->name('account_settings');
        Volt::route('overview', 'pages.profile.userprofile')->name('account_overview');
        Volt::route('changepass', 'pages.profile.changepass')->name('account_changepass');
        Volt::route('sessions', 'pages.profile.sessions')->name('account_sessions');
    });


Route::view('/', 'welcome')->middleware(['guest']);
Route::view('/test', 'test');



require __DIR__ . '/auth.php';
