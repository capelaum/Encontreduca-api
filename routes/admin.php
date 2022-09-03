<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::group([
    'as' => 'admin.auth.',
    'controller' => AuthController::class,
], function () {
    Route::post('login', 'login')
        ->name('login');

    Route::group([
        'middleware' => ['auth:sanctum', 'verified', 'is_admin']
    ], function () {
        Route::post('logout', 'logout')
            ->name('logout');
    });

});

Route::group([
    'as' => 'admin.dashboard.',
    'controller' => DashboardController::class,
    'middleware' => ['auth:sanctum', 'verified', 'is_admin']
], function () {
    Route::get('dashboard', 'index')
        ->name('index');
});

Route::group([
    'as' => 'admin.users.',
    'controller' => UserController::class,
    'middleware' => ['auth:sanctum', 'verified', 'is_admin']
], function () {
    Route::delete('users/{user}/avatar', 'deleteAvatar')
        ->name('delete.avatar');

    Route::apiResource('users', UserController::class);

});
