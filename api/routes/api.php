<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Authentication routes prefixed with 'auth'
Route::prefix('auth')->group(function () {
    // Register route
    Route::post('/register', [AuthController::class, 'register']);

    // Login route
    Route::post('/login', [AuthController::class, 'login']);

    Route::post('/logout', [AuthController::class, 'logout']);

});
