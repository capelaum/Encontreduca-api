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
    SupportController
};


Route::apiResources([
    'categories' => CategoryController::class,
    'motives' => MotiveController::class
]);


Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::apiResource('resources/complaints', ResourceComplaintController::class, [
        'as' => 'resources'
    ]);

    Route::apiResource('reviews/complaints', ReviewComplaintController::class, [
        'as' => 'reviews'
    ]);

    Route::group(['prefix' => 'resources'], function () {
        Route::post('/', [ResourceController::class, 'store'])
            ->name('resources.store');

        Route::get('/{resource}/votes', [ResourceController::class, 'votes'])
            ->name('resources.votes');
    });

    Route::group(['prefix' => 'users'], function () {
        Route::delete('{user}/avatar', [UserController::class, 'deleteAvatar']);

        Route::post('/resources', [UserController::class, 'storeResource']);
        Route::delete('{user}/resources/{resource}', [UserController::class, 'deleteResource']);
    });

    Route::apiResources([
        'users' => UserController::class,
        'supports' => SupportController::class,
        'resources/changes' => ResourceChangeController::class,
        'resources/votes' => ResourceVoteController::class,
        'reviews' => ReviewController::class
    ]);
});

Route::group([
    'prefix' => 'resources',
    'controller' => ResourceController::class
], function () {
    Route::get('/', 'index')->name('resources.index');
    Route::get('/{resource}', 'show')->name('resources.show');
    Route::get('/{resource}/reviews', 'reviews')->name('resources.reviews');
});

Route::group([
    'prefix' => 'reviews',
    'controller' => ReviewController::class
], function () {
    Route::get('/', 'index')->name('reviews.index');
    Route::get('/{review}', 'show')->name('reviews.show');
});
