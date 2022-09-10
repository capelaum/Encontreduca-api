<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{AuthController,
    CategoryController,
    DashboardController,
    MotiveController,
    ResourceChangeController,
    ResourceComplaintController,
    ResourceController,
    ResourceVoteController,
    ReviewComplaintController,
    ReviewController,
    SupportController,
    UserController
};

Route::group([
    'as' => 'admin.auth.',
    'controller' => AuthController::class,
], function () {
    Route::post('login', 'login')
        ->name('login');

    Route::post('logout', 'logout')
        ->middleware(['auth:sanctum', 'verified', 'is_admin'])
        ->name('logout');
});

Route::group([
    'middleware' => ['auth:sanctum', 'verified', 'is_admin'],
    'as' => 'admin.',
], function () {
    Route::group([
        'as' => 'dashboard.',
        'controller' => DashboardController::class,
    ], function () {
        Route::get('dashboard', 'index')
            ->name('index');
    });

    Route::group([
        'controller' => UserController::class,
    ], function () {
        Route::delete('users/{user}/avatar', 'deleteAvatar')
            ->name('users.delete.avatar');

        Route::apiResource('users', UserController::class);
    });

    Route::apiResource(
        'resources/votes',
        ResourceVoteController::class
    );

    Route::apiResource(
        'resources/complaints',
        ResourceComplaintController::class,
        [
            'only' => ['index', 'show', 'destroy'],
            'as' => 'resources'
        ]
    );

    Route::apiResource(
        'resources/changes',
        ResourceChangeController::class,
        [
            'only' => ['index', 'show', 'destroy'],
            'as' => 'resources'
        ]
    );

    Route::group([
        'controller' => ResourceController::class,
    ], function () {
        Route::get('resources/{resource}/votes', 'votes')
            ->name('resources.votes');

        Route::get('resources/{resource}/reviews', 'reviews')
            ->name('resources.reviews');

        Route::get('resources/{resource}/complaints', 'complaints')
            ->name('resources.complaints');

        Route::apiResource('resources', ResourceController::class);
    });

    Route::apiResource(
        'reviews/complaints',
        ReviewComplaintController::class,
        [
            'only' => ['index', 'show', 'destroy'],
            'as' => 'reviews'
        ]
    );

    Route::apiResource(
        'reviews',
        ReviewController::class
    );

    Route::apiResource(
        'categories',
        CategoryController::class
    );

    Route::apiResource(
        'motives',
        MotiveController::class
    );

    Route::apiResource(
        'supports',
        SupportController::class,
        [
            'only' => ['index', 'show', 'destroy']
        ]
    );
});
