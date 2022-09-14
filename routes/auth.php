<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\{
    AuthController,
    ResetPasswordController,
    VerifyEmailController
};


Route::group([
    'as' => 'auth.',
    'controller' => AuthController::class
], function () {
    Route::post('register', 'register')
        ->name('register');

    Route::post('/login/{provider}', 'loginWithProvider')
        ->name('login.provider');

    Route::post('login', 'login')
        ->name('login');

    Route::group([
        'middleware' => ['auth:sanctum', 'verified']
    ], function () {
        Route::get('user', 'getAuthUser')
            ->name('user');

        Route::post('logout', 'logout')
            ->name('logout');
    });
});

Route::group([
    'as' => 'verification.',
    'prefix' => 'email/verify',
    'middleware' => 'throttle:6,1',
    'controller' => VerifyEmailController::class
], function () {
    Route::get('{id}/{hash}', 'verify')
        ->middleware(['signed'])
        ->name('verify');

    Route::post('resend', 'send')
        ->middleware(['auth:sanctum'])
        ->name('send');
});

Route::controller(ResetPasswordController::class)->group(function () {
    Route::post('/forgot-password', 'email')
        ->name('password.email');

    Route::get('/reset-password/{token}', 'reset')
        ->name('password.reset');

    Route::post('/reset-password', 'update')
        ->name('password.update');
});


