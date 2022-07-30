<?php

use App\Http\Controllers\{
    UserController,
    ResourceController,
    CategoryController,
    ReviewController,
    MotiveController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');

    Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
});

Route::prefix('resources')->group(function () {
    Route::get('/', [ResourceController::class, 'index'])->name('resources.index');

    Route::get('/{resource}', [ResourceController::class, 'show'])->name('resources.show');

    Route::post('/', [ResourceController::class, 'store'])->name('resources.store');
});

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
});

Route::prefix('motives')->group(function () {
    Route::get('/', [MotiveController::class, 'index'])->name('motives.index');
});

Route::prefix('reviews')->group(function () {
    Route::get('/', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/', [ReviewController::class, 'store'])->name('reviews.store');

    Route::put('/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});
