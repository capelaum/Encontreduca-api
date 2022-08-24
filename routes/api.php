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


Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::group(['prefix' => 'users'], function () {
        Route::delete('{user}/avatar', [UserController::class, 'deleteAvatar'])
            ->name('users.delete.avatar');

        Route::get('/votes', [UserController::class, 'votes'])
            ->name('users.votes');

        Route::post('/resources/{resource}', [UserController::class, 'storeResource'])
            ->name('users.store.resource');

        Route::delete('resources/{resource}', [UserController::class, 'deleteResource'])
            ->name('users.delete.resource');
    });

    Route::group(['prefix' => 'resource/user'], function () {
        Route::post('/{resource}', [ResourceUserController::class, 'store'])
            ->name('resource.user.store');

        Route::delete('/{resource}', [ResourceUserController::class, 'destroy'])
            ->name('resource.user.destroy');
    });

    Route::group(['prefix' => 'resources'], function () {
        Route::post('/', [ResourceController::class, 'store'])
            ->name('resources.store');

        Route::get('/{resource}/votes', [ResourceController::class, 'votes'])
            ->name('resources.votes');

        Route::apiResource('complaints', ResourceComplaintController::class, [
            'as' => 'resources'
        ]);

        Route::apiResource('changes', ResourceChangeController::class, [
            'as' => 'resources'
        ]);
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
