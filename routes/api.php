<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\V1\EducationalResource\ResourceController;


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
        Route::delete('{user}/avatar', ['uses' => 'UserController@deleteAvatar']);

        Route::post('/resources', ['uses' => 'UserController@storeResource']);
        Route::delete('{user}/resources/{resource}', ['uses' => 'UserController@deleteResource']);
    });
});


