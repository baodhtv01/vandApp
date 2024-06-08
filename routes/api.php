<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout']);
Route::post('register', [AuthController::class, 'register']);


Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('stores', StoreController::class);
    Route::apiResource('products', ProductController::class);
});
