<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->middleware(['guest']);
Route::view('/test', 'test');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
