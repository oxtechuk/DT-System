<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\DealController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\RoomController;
use App\Http\Controllers\Api\V1\SettingController;
use App\Http\Controllers\Api\V1\ShiftController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — DT-System Primary Mobile & Workspace API
| Version: V1
| Base URL: /api/v1/
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->name('api.v1.')->group(function () {

    // ── Public Auth ────────────────────────────────────────────────────────
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('login');
    });

    // ── Public Settings & Branding ─────────────────────────────────────────
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');

    // ── Protected API Endpoints ────────────────────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {

        // Auth management
        Route::prefix('auth')->name('auth.')->group(function () {
            Route::get('me',      [AuthController::class, 'me'])->name('me');
            Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        });

        // Dashboard & Overview
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Customers
        Route::apiResource('customers', CustomerController::class)->only(['index', 'store', 'show', 'update']);

        // Rooms & Workspaces
        Route::get('rooms', [RoomController::class, 'index'])->name('rooms.index');

        // Deals (Active Sessions)
        Route::get('deals/active',               [DealController::class, 'active'])->name('deals.active');
        Route::apiResource('deals', DealController::class)->only(['index', 'store', 'show']);
        Route::post('deals/{deal}/close',        [DealController::class, 'close'])->name('deals.close');
        Route::post('deals/{deal}/pay',          [DealController::class, 'pay'])->name('deals.pay');
        Route::post('deals/{deal}/items',        [DealController::class, 'addItem'])->name('deals.add-item');
        Route::delete('deals/{deal}/items/{item}', [DealController::class, 'removeItem'])->name('deals.remove-item');

        // Products & Cafe Catalog
        Route::get('products', [ProductController::class, 'index'])->name('products.index');

        // Cashier Shifts
        Route::prefix('shifts')->name('shifts.')->group(function () {
            Route::get('current',        [ShiftController::class, 'current'])->name('current');
            Route::post('open',          [ShiftController::class, 'open'])->name('open');
            Route::post('{shift}/close', [ShiftController::class, 'close'])->name('close');
        });

        // Settings updates
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    });

});
