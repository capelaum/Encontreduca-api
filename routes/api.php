<?php

use App\Http\Controllers\Api\V1\{
    CategoryController,
    MotiveController,
    ResourceChangeController,
    ResourceComplaintController,
    ResourceController,
    ResourceVoteController,
    ReviewComplaintController,
    ReviewController,
    SupportRequestController,
    // UserController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function () {
    Route::apiResource('users', UserController::class);

    Route::prefix('users')->group(function () {
        Route::delete('{user}/avatar', ['uses' => 'UserController@deleteAvatar']);

        Route::post('/resources', ['uses' => 'UserController@storeResource']);
        Route::delete('{user}/resources/{resource}', ['uses' => 'UserController@deleteResource']);
    });

    Route::apiResource('resources/complaints', ResourceComplaintController::class);
    Route::apiResource('resources/changes', ResourceChangeController::class);
    Route::apiResource('resources/votes', ResourceVoteController::class);
    Route::apiResource('resources', ResourceController::class);

    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('motives', MotiveController::class);


    Route::apiResource('reviews/complaints', ReviewComplaintController::class);
    Route::apiResource('reviews', ReviewController::class);

    Route::apiResource('supports', SupportController::class);
});
