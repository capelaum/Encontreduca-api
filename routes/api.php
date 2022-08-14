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

Route::prefix('resources')->group(function () {
    Route::get('/', [ResourceController::class, 'index']);
    Route::get('/{resource}', [ResourceController::class, 'show']);
});

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::apiResources([
        'users' => UserController::class,
        'supports' => SupportController::class,
        'resources/complaints' => ResourceComplaintController::class,
        'resources/changes' => ResourceChangeController::class,
        'resources/votes' => ResourceVoteController::class,
        'reviews/complaints' => ReviewComplaintController::class,
        'reviews' => ReviewController::class
    ]);

    Route::post('/', [ResourceController::class, 'store']);

    Route::prefix('users')->group(function () {
        Route::delete('{user}/avatar', [UserController::class, 'deleteAvatar']);

        Route::post('/resources', [UserController::class, 'storeResource']);
        Route::delete('{user}/resources/{resource}', [UserController::class, 'deleteResource']);
    });
});


