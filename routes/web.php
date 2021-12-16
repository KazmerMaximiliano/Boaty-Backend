<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// AUTH
Route::post('login', [App\Http\Controllers\API\AuthController::class, 'login']);
Route::post('logout', [App\Http\Controllers\API\AuthController::class, 'logout']);

// SOCIAL AUTH
Route::get('auth/google', [ App\Http\Controllers\API\AuthController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [ App\Http\Controllers\API\AuthController::class, 'handleGoogleCallback']);

Route::get('auth/facebook', [ App\Http\Controllers\API\AuthController::class, 'redirectToFacebook']);
Route::get('auth/facebook/callback', [ App\Http\Controllers\API\AuthController::class, 'handleFacebookCallback']);

// VUE CAPTURE
Route::get('/{vue_capture?}', function () {
    return view('welcome');
})->where('vue_capture', '[\/\w\.-]*');
