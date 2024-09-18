<?php

use App\Events\NumberPosted;
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