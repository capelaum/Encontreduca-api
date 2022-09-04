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


Route::apiResources([
    'categories' => CategoryController::class,
    'motives' => MotiveController::class
]);

Route::group([
    'middleware' => ['auth:sanctum', 'verified']
], function () {
    Route::group([
        'prefix' => 'users',
        'as' => 'users.'
    ], function () {
        Route::delete('{user}/avatar', [UserController::class, 'deleteAvatar'])
            ->name('delete.avatar');

        Route::get('/votes', [UserController::class, 'votes'])
            ->name('votes');
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

    Route::group([
        'prefix' => 'resources',
        'as' => 'resources.'
    ], function () {
        Route::post('/', [ResourceController::class, 'store'])
            ->name('store');

        Route::apiResource('complaints', ResourceComplaintController::class);

        Route::apiResource('changes', ResourceChangeController::class);
    });

    Route::apiResource('reviews/complaints', ReviewComplaintController::class, [
        'as' => 'reviews'
    ]);

    Route::apiResources([
        'users' => UserController::class,
        'supports' => SupportController::class,
        'resources/votes' => ResourceVoteController::class,
        'reviews' => ReviewController::class
    ]);
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
