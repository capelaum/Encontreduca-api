<?php

use App\Http\Controllers\{
    UserController,
    ResourceController,
    CategoryController,
    ReviewController,
    MotiveController,
    ResourceChangeController,
    ResourceComplaintController,
    ReviewComplaintController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('users.index');
    Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
    Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('{user}/avatar', [UserController::class, 'deleteAvatar'])->name('users.deleteAvatar');

    Route::post('/resources', [UserController::class, 'storeResource'])->name('users.storeResource');
    Route::delete('{user}/resources/{resource}', [UserController::class, 'deleteResource'])->name('users.deleteResource');
});

Route::prefix('resources')->group(function () {
    Route::prefix('complaints')->group(function () {
        Route::get('/', [ResourceComplaintController::class, 'index'])->name('resources.complaints.index');
        Route::get('/{resourceComplaint}', [ResourceComplaintController::class, 'show'])->name('resources.complaints.show');
        Route::post('/', [ResourceComplaintController::class, 'store'])->name('resources.complaints.store');
    });

    Route::prefix('changes')->group(function () {
        Route::get('/', [ResourceChangeController::class, 'index'])->name('resources.changes.index');
        Route::post('/', [ResourceChangeController::class, 'store'])->name('resources.changes.store');
        Route::get('/{resourceChange}', [ResourceChangeController::class, 'show'])->name('resources.changes.show');
    });

    Route::get('/', [ResourceController::class, 'index'])->name('resources.index');
    Route::post('/', [ResourceController::class, 'store'])->name('resources.store');
    Route::get('/{resource}', [ResourceController::class, 'show'])->name('resources.show');
});

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('categories.index');
});

Route::prefix('motives')->group(function () {
    Route::get('/', [MotiveController::class, 'index'])->name('motives.index');
});

Route::prefix('reviews')->group(function () {
    Route::prefix('complaints')->group(function () {
        Route::get('/', [ReviewComplaintController::class, 'index'])->name('reviews.complaints.index');
        Route::get('/{reviewComplaint}', [ReviewComplaintController::class, 'show'])->name('reviews.complaints.show');
        Route::post('/', [ReviewComplaintController::class, 'store'])->name('reviews.complaints.store');
    });

    Route::get('/', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});
