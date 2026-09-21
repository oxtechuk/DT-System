<?php

use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\DealController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\RoomController;
use App\Http\Controllers\Api\V1\SettingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — DT-System Workspace Management
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->name('api.v1.')->group(function () {

    // ── Dashboard ──
    Route::get('dashboard', [DashboardController::class, 'index']);

    // ── Customers ──
    Route::apiResource('customers', CustomerController::class)->only(['index', 'store', 'show']);

    // ── Rooms ──
    Route::get('rooms', [RoomController::class, 'index']);

    // ── Deals (Sessions) ──
    Route::get('deals/active', [DealController::class, 'active']);
    Route::apiResource('deals', DealController::class)->only(['store', 'show']);
    Route::post('deals/{deal}/close',        [DealController::class, 'close']);
    Route::post('deals/{deal}/pay',          [DealController::class, 'pay']);
    Route::post('deals/{deal}/items',        [DealController::class, 'addItem']);
    Route::delete('deals/{deal}/items/{item}', [DealController::class, 'removeItem']);

    // ── Products ──
    Route::get('products', [ProductController::class, 'index']);

    // ── Settings (Branding, Logo, Colors) ──
    Route::get('settings', [SettingController::class, 'index']);
    Route::post('settings', [SettingController::class, 'update']);

});
