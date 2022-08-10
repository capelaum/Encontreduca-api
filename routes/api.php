<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1'], function () {
    Route::apiResources([
        'users' => UserController::class,
        'categories' => CategoryController::class,
        'motives' => MotiveController::class,
        'supports' => SupportController::class,
        'resources/complaints' => ResourceComplaintController::class,
        'resources/changes' => ResourceChangeController::class,
        'resources/votes' => ResourceVoteController::class,
        'resources' => ResourceController::class,
        'reviews/complaints' => ReviewComplaintController::class,
        'reviews' => ReviewController::class
    ]);

    Route::prefix('users')->group(function () {
        Route::delete('{user}/avatar', ['uses' => 'UserController@deleteAvatar']);

        Route::post('/resources', ['uses' => 'UserController@storeResource']);
        Route::delete('{user}/resources/{resource}', ['uses' => 'UserController@deleteResource']);
    });
});
