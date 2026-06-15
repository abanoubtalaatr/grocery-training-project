<?php

use App\Http\Controllers\Api\V1\Auth\AuthenticatedUserController;
use App\Http\Controllers\Api\V1\Auth\RegisteredUserController;
use App\Http\Controllers\Api\V1\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Api\Auth\GoogleAuthController;
use Illuminate\Support\Facades\Route;



Route::prefix('auth')->group(function () {
    // without protected routes
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

// protected routes
Route::middleware('auth:sanctum')->group(function () {
    // authenticated routes
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);

    // show and delete authenticated user
    Route::get('/user', [AuthenticatedUserController::class, 'show']);
    Route::delete('/user', [AuthenticatedUserController::class, 'destroy']);


    // verify 
});


// public routes -- Authentication routes that don't require authentication
Route::prefix('auth')->group(function () {

    Route::post('/google', [GoogleAuthController::class, 'login']);
});