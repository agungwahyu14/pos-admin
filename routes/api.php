<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;

// Public routes
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Users
    Route::apiResource('users', UserController::class);

    // Shifts
    Route::prefix('shifts')->group(function () {
        Route::get('/', [ShiftController::class, 'index']);
        Route::get('/current', [ShiftController::class, 'current']);
        Route::post('/open', [ShiftController::class, 'open']);
        Route::post('/close', [ShiftController::class, 'close']);
    });

    // Categories
    Route::apiResource('categories', CategoryController::class);

    // Products
    Route::patch('/products/{product}/stock', [ProductController::class, 'updateStock']);
    Route::apiResource('products', ProductController::class);

    // Orders
    Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus']);
    Route::apiResource('orders', OrderController::class)->except(['update', 'destroy']);

    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/dashboard', [ReportController::class, 'dashboard']);
        Route::get('/sales', [ReportController::class, 'sales']);
        Route::get('/inventory', [ReportController::class, 'inventory']);
    });

    // Settings
    Route::get('/settings', [SettingController::class, 'index']);
    Route::put('/settings', [SettingController::class, 'update']);
    Route::get('/settings/printer', [\App\Http\Controllers\PrinterSettingController::class, 'index']);
    Route::put('/settings/printer', [\App\Http\Controllers\PrinterSettingController::class, 'update']);
});
