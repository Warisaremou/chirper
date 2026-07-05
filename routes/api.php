<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ChirpController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    });

    Route::get('/chirps', [ChirpController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::get('/users/{user}/chirps', [UserController::class, 'chirps']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/chirps', [ChirpController::class, 'store']);
        Route::get('/chirps/{chirp}', [ChirpController::class, 'show']);
        Route::patch('/chirps/{chirp}', [ChirpController::class, 'update']);
        Route::delete('/chirps/{chirp}', [ChirpController::class, 'destroy']);
        Route::post('/chirps/{chirp}/like', [ChirpController::class, 'like']);
        Route::post('/chirps/{chirp}/unlike', [ChirpController::class, 'unlike']);

        Route::patch('/users/profile', [UserController::class, 'update']);
        Route::post('/users/{user}/follow', [UserController::class, 'follow']);
        Route::post('/users/{user}/unfollow', [UserController::class, 'unfollow']);
    });
});
