<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Api\V1\AuthController;


Route::post('api/v1/register', [AuthController::class, 'register']);
Route::post('api/v1/login', [AuthController::class, 'login']);
