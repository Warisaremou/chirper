<?php

use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\Logout;
use App\Http\Controllers\Auth\Profile;
use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\ChirpController;
use App\Http\Controllers\NotificationController;
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

Route::middleware('guest')->group(function () {
    Route::view('/register', 'auth.register')->name('register');
    Route::post('/register', Register::class);

    Route::view('/login', 'auth.login')->name('login');
    Route::post('/login', Login::class);
});

Route::post('/logout', Logout::class)
    ->middleware('auth')
    ->name('logout');

Route::controller(Profile::class)->group(function () {
    Route::get('/settings', 'index')->name('profile');

    Route::prefix('/profile')->name('profile.')->group(function () {
        Route::patch('/edit', 'edit')->name('edit');
        Route::post('/edit/avatar', 'editAvatar')->name('edit.avatar');
        Route::get('/avatar/{user}', 'showAvatar')->name('show.avatar');
        Route::post('/follow/{user}', 'follow')->name('follow');
        Route::post('/unfollow/{user}', 'unfollow')->name('unfollow');
    });
})->middleware('auth');

Route::controller(NotificationController::class)->middleware('auth')->group(function () {
    Route::prefix('/notifications')->name('notifications.')->group(function () {
        Route::post('/push/subscribe', 'subscribe')->name('push.subscribe');
        Route::post('/push/unsubscribe', 'unsubscribe')->name('push.unsubscribe');
        Route::patch('/read', 'markAllAsRead')->name('readAll');
        Route::patch('/{id}/read', 'markAsRead')->name('read');
    });
});