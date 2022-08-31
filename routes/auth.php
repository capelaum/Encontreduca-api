<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\{
    AuthController,
    ResetPasswordController,
    VerifyEmailController
};


Route::controller(AuthController::class)->group(function () {
    Route::get('user', 'getAuthUser')
        ->middleware(['auth:sanctum', 'verified'])
        ->name('auth.user');

    Route::post('register', 'register')
        ->name('auth.register');

    Route::post('/login/{provider}', 'loginWithProvider')
        ->name('auth.login.provider');

    Route::post('login', 'login')
        ->name('auth.login');

    Route::post('logout', 'logout')
        ->middleware(['auth:sanctum', 'verified'])
        ->name('auth.logout');

});


Route::group([
    'prefix' => 'email/verify',
    'middleware' => 'throttle:6,1',
    'controller' => VerifyEmailController::class
], function () {
    Route::get('/{id}/{hash}', 'verify')
        ->middleware(['signed'])
        ->name('verification.verify');

    Route::post('/resend', 'send')
        ->middleware(['auth:sanctum'])
        ->name('verification.send');
});

Route::controller(ResetPasswordController::class)->group(function () {
    Route::post('/forgot-password', 'email')
        ->name('password.email');

    Route::get('/reset-password/{token}', 'reset')
        ->name('password.reset');

    Route::post('/reset-password', 'update')
        ->name('password.update');
});


