<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\{
    AuthController,
    ResetPasswordController,
    VerifyEmailController
};


Route::post('register', [AuthController::class, 'register'])
    ->name('auth.register');

Route::post('login', [AuthController::class, 'login'])
    ->name('auth.login');

Route::post('logout', [AuthController::class, 'logout'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('auth.logout');

Route::get('user', [AuthController::class, 'getAuthUser'])
    ->middleware(['auth:sanctum', 'verified'])
    ->name('auth.user');

Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verify/resend', [VerifyEmailController::class, 'send'])
    ->middleware(['auth:sanctum', 'throttle:6,1'])
    ->name('verification.send');

Route::post('/forgot-password', [ResetPasswordController::class, 'email'])
    ->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'reset'])
    ->name('password.reset');

Route::post('/reset-password', [ResetPasswordController::class, 'update'])
    ->name('password.update');
