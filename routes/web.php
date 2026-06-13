<?php

use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\Logout;
use App\Http\Controllers\Auth\Profile;
use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\ChirpController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ChirpController::class, 'index'])->name('home');
Route::resource('chirps', ChirpController::class)->only([
    'store',
    'edit',
    'update',
    'destroy',
])->middleware('auth');

Route::controller(ChirpController::class)->group(function () {
    Route::post('/chirps/{chirp}/like', 'like')->name('chirp.like');
    Route::post('/chirps/{chirp}/unlike', 'unlike')->name('chirp.unlike');
})->middleware('auth');

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

Route::controller(Profile::class)->group(function () {
    Route::get('/settings', 'index')->name('profile');
    Route::patch('/profile/edit', 'edit')->name('profile.edit');
    Route::post('/profile/edit/avatar', 'editAvatar')->name('profile.edit.avatar');
    Route::get('/profile/avatar', 'showAvatar')->name('profile.show.avatar');
    Route::post('/profile/follow/{user}', 'follow')->name('profile.follow');
    Route::post('/profile/unfollow/{user}', 'unfollow')->name('profile.unfollow');
})->middleware('auth');