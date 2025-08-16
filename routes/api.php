<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\BookController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::middleware('auth:api')->group(function () {
    Route::post('books', [BookController::class, 'store']);
    Route::post('logout', [AuthController::class, 'logout']);
});
