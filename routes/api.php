<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\DealController;
use App\Http\Controllers\Api\V1\OrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes — /api/v1/
|--------------------------------------------------------------------------
|
| All routes are versioned under /api/v1/
| Authentication: Laravel Sanctum (token-based)
|
*/

Route::prefix('v1')->name('v1.')->group(function () {

    // ── Public: Auth ─────────────────────────────────────────────────────────
    Route::prefix('auth')->name('auth.')->group(function () {
        Route::post('login',  [AuthController::class, 'login'])->name('login');
    });

    // ── Protected ─────────────────────────────────────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::prefix('auth')->name('auth.')->group(function () {
            Route::post('logout', [AuthController::class, 'logout'])->name('logout');
            Route::get('me',     [AuthController::class, 'me'])->name('me');
        });

        // Customers
        Route::prefix('customers')->name('customers.')->group(function () {
            Route::get('/',         [CustomerController::class, 'index'])->name('index');
            Route::post('/',        [CustomerController::class, 'store'])->name('store');
            Route::get('/{customer}',    [CustomerController::class, 'show'])->name('show');
            Route::put('/{customer}',    [CustomerController::class, 'update'])->name('update');
        });

        // Deals (Sessions)
        Route::prefix('deals')->name('deals.')->group(function () {
            Route::get('/',               [DealController::class, 'index'])->name('index');
            Route::post('/',              [DealController::class, 'store'])->name('store');
            Route::get('/{deal}',         [DealController::class, 'show'])->name('show');
            Route::post('/{deal}/close',  [DealController::class, 'close'])->name('close');
            Route::patch('/{deal}/time',  [DealController::class, 'adjustTime'])->name('adjust-time');
        });

        // Orders
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/{order}',                     [OrderController::class, 'show'])->name('show');
            Route::post('/{order}/items',              [OrderController::class, 'addItem'])->name('add-item');
            Route::delete('/{order}/items/{item}',     [OrderController::class, 'removeItem'])->name('remove-item');
            Route::post('/{order}/payments',           [OrderController::class, 'addPayment'])->name('add-payment');
            Route::post('/{order}/close',              [OrderController::class, 'close'])->name('close');
        });

        // Rooms (read-only in Phase 1)
        Route::get('rooms', fn() => response()->json(\App\Models\Room::active()->get()))->name('rooms.index');

        // Workspace Types (read-only)
        Route::get('workspace-types', fn() => response()->json(\App\Models\WorkspaceType::active()->get()))->name('workspace-types.index');

        // Products (read-only listing in Phase 1)
        Route::get('products', fn() => response()->json(
            \App\Models\Product::with('category')->active()->orderBy('name')->get()
        ))->name('products.index');

    });

});
