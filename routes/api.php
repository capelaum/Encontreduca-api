<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\V1\EducationalResource\{
    ResourceController,
    ResourceComplaintController,
    ResourceChangeController,
    ResourceVoteController
};
use \App\Http\Controllers\Api\V1\Review\{
    ReviewController,
    ReviewComplaintController
};

use \App\Http\Controllers\Api\V1\{
    UserController,
    CategoryController,
    MotiveController,
    SupportController,
    ResourceUserController
};


Route::get('categories', [CategoryController::class, 'index'])
    ->name('categories.index');

Route::get('motives', [MotiveController::class, 'index'])
    ->name('motives.index');

Route::group([
    'middleware' => ['auth:sanctum', 'verified']
], function () {
    Route::group([
        'prefix' => 'users',
        'as' => 'users.',
        'controller' => UserController::class,
    ], function () {
        Route::delete('{user}/avatar', 'deleteAvatar')
            ->name('delete.avatar');

        Route::put('/{user}', 'update')
            ->name('update');

        Route::patch('/{user}', 'update')
            ->name('update');

        Route::delete('/{user}', 'destroy')
            ->name('destroy');
    });

    Route::group([
        'prefix' => 'resources',
        'as' => 'resources.'
    ], function () {
        Route::post('/', [ResourceController::class, 'store'])
            ->name('store');

        Route::post('/votes', [ResourceVoteController::class, 'store'])
            ->name('votes.store');

        Route::put('/votes/{vote}', [ResourceVoteController::class, 'update'])
            ->name('votes.update');

        Route::patch('/votes/{vote}', [ResourceVoteController::class, 'update'])
            ->name('votes.update');

        Route::post('/complaints', [ResourceComplaintController::class, 'store'])
            ->name('complaints.store');

        Route::post('/changes', [ResourceChangeController::class, 'store'])
            ->name('changes.store');
    });

    Route::group([
        'prefix' => 'resource/user',
        'as' => 'resource.user.'
    ], function () {
        Route::post('/', [ResourceUserController::class, 'store'])
            ->name('store');

        Route::delete('/{resource}', [ResourceUserController::class, 'destroy'])
            ->name('destroy');
    });

    Route::apiResource('reviews', ReviewController::class);

    Route::post('reviews/complaints', [ReviewComplaintController::class, 'store'])
        ->name('reviews.complaints.store');

    Route::post('supports', [SupportController::class, 'store'])
        ->name('supports.store');
});

Route::group([
    'prefix' => 'resources',
    'as' => 'resources.',
    'controller' => ResourceController::class
], function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{resource}', 'show')->name('show');
    Route::get('/{resource}/reviews', 'reviews')->name('reviews');
});

Route::group([
    'prefix' => 'reviews',
    'controller' => ReviewController::class
], function () {
    Route::get('/', 'index')->name('reviews.index');
    Route::get('/{review}', 'show')->name('reviews.show');
});
