<?php

use App\Http\Controllers\DeviceController;
use App\Http\Controllers\DeviceLogs;
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
        'session_id' => session()->getId()
    ]);
});

Route::middleware(['auth', 'isNoPassword', 'verified'])->group(function () {

    Volt::route('dashboard', 'pages.dashboard')->name('dashboard');
    Route::view('user-management', 'usermanagement')->name('user_management');
    Route::view('laboratory', 'laboratory')->name('laboratory');
    Route::view('computer-devices', 'computerdevices')->name('computerdevices');
    Route::get('/devicegraph/{id}', [DeviceController::class, 'show'])->name('devicegraph');
    Route::get('/devicelogs/{id}', [DeviceLogs::class, 'show'])->name('devicelogs');
    Volt::route('nofications', 'pages.notifications')->name('notifications');
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
