<?php

use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\Logout;
use App\Http\Controllers\Auth\Profile;
use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\ChirpController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ChirpController::class, 'index']);
Route::resource('chirps', ChirpController::class)->only([
    'store',
    'edit',
    'update',
    'destroy'
])->middleware('auth');

Route::view('/register', 'auth.register')
    ->middleware('guest')
    ->name('register');

Route::post('/register', Register::class)
    ->middleware('guest');

Route::view('/login', 'auth.login')
    ->middleware('guest')
    ->name('login');

Route::post('/login', Login::class)
    ->middleware('guest');

Route::post('/logout', Logout::class)
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/settings', [Profile::class, 'index'])->name('profile');
    Route::patch('/profile/edit', [Profile::class, 'edit'])->name('profile.edit');
});