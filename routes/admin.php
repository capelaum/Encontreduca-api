<?php

use App\Http\Controllers\Admin\AuthController;
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
