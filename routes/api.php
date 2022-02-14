<?php

use Illuminate\Support\Facades\Route;

// AUTH
Route::post('register', [App\Http\Controllers\API\AuthController::class, 'register']);
Route::post('login', [App\Http\Controllers\API\AuthController::class, 'apiLogin']);
Route::post('logout', [App\Http\Controllers\API\AuthController::class, 'apiLogout']);

// SOCIAL AUTH
Route::post('auth/google', [App\Http\Controllers\API\AuthController::class, 'google']);
Route::post('auth/facebook', [App\Http\Controllers\API\AuthController::class, 'facebook']);
Route::post('auth/apple', [App\Http\Controllers\API\AuthController::class, 'apple']);

// BOATS
Route::get('boats/{search?}', [App\Http\Controllers\API\BoatController::class, 'index']);

// CONECT STRIPE ACCOUNT
Route::get('connect', [App\Http\Controllers\API\UsersController::class,'connectAccount']);
Route::post('save-extra',[App\Http\Controllers\API\UsersController::class,'saveExtra']);

Route::middleware('auth:sanctum')->group(function () {
    // USERS
    Route::get('user', [App\Http\Controllers\API\UsersController::class, 'user']);
    Route::post('update_account', [App\Http\Controllers\API\UsersController::class, 'updateAccount']);
    Route::get('add-owner-role', [App\Http\Controllers\API\AuthController::class, 'addOwnerRole']);
    Route::resource('users', App\Http\Controllers\API\UsersController::class, ['except' => ['create', 'edit', 'show']]);

    // CRYPTO ADDRESS
    Route::post('set-crypto-address', [App\Http\Controllers\API\UsersController::class,'setCryptoAddress']);
    Route::get('get-crypto-address', [App\Http\Controllers\API\UsersController::class, 'getOwnerCryptoAdders']);

    // BOATS
    Route::resource('boats', App\Http\Controllers\API\BoatController::class, ['except' => ['index', 'show',  'create', 'edit']]);
    Route::get('boats/show/{boat}', [App\Http\Controllers\API\BoatController::class, 'show']);
    Route::get('user-favourites-boats',[App\Http\Controllers\API\BoatController::class, 'favouriteBoats']);
    Route::get('owner-boats', [App\Http\Controllers\API\BoatController::class, 'boatsByOwner']);

    // RATINGS
    Route::resource('ratings', App\Http\Controllers\API\RatingController::class);

    // RESERVATIONS
    Route::resource('reservations', App\Http\Controllers\API\ReservationController::class);
    Route::get('cancel-reservation/{reservation}',[App\Http\Controllers\API\ReservationController::class,'cancel']);
    Route::post('confirm', [App\Http\Controllers\API\ReservationController::class, 'confirmPayment']);
    Route::get('owner-wallet/{id}', [App\Http\Controllers\API\PaymentController::class,'getOwnerWallet']);
    Route::post('cripto-pay',[App\Http\Controllers\API\PaymentController::class,'payOffline']);

    // DEBTS
    Route::get('debts', [App\Http\Controllers\API\PaymentController::class, 'getDebts']);
    Route::post('debts/confirm', [App\Http\Controllers\API\PaymentController::class, 'confirmPaymentOffline']);

    // AVAILAVILITIES
    Route::post('availavilities', [App\Http\Controllers\API\AvailavilityController::class,'store']);
    Route::put('sub-availavilities/{availavility}', [App\Http\Controllers\API\AvailavilityController::class, 'reserveDays']);
    Route::put('add-availavilities/{boat}', [App\Http\Controllers\API\AvailavilityController::class, 'rearrangeDays']);
    Route::put('update-availavilities/{boat}',[App\Http\Controllers\API\AvailavilityController::class, 'updateAvailavilities']);

    // FAVOURITES
    Route::post('boat/favourite',[App\Http\Controllers\API\PreferenceController::class,'favourite']);
    Route::post('boat/unfavourite',[App\Http\Controllers\API\PreferenceController::class,'unfavourite']);
});
