<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — DT-System Dashboard
|--------------------------------------------------------------------------
*/

// ── Authentication Routes ──
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.submit');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// ── Core Dashboard & Screens ──
Route::get('/', [RoutingController::class, 'root'])->name('root');
Route::get('dashboard', [RoutingController::class, 'root'])->name('dashboard');

// Cashier POS
Route::get('cashier', [\App\Http\Controllers\CashierController::class, 'index'])->name('cashier');

// Settings & Branding
Route::get('settings/general', function () {
    return view('settings.general');
})->name('settings.general');

// ── Workspace Operations ──
Route::get('customers', [WorkspaceController::class, 'customers'])->name('customers.index');
Route::post('customers', [WorkspaceController::class, 'storeCustomer'])->name('customers.store');

Route::get('rooms', [WorkspaceController::class, 'rooms'])->name('rooms.index');

Route::get('bookings', [WorkspaceController::class, 'bookings'])->name('bookings.index');
Route::post('bookings', [WorkspaceController::class, 'storeBooking'])->name('bookings.store');

Route::get('deals/active', [WorkspaceController::class, 'activeDeals'])->name('deals.active');

Route::get('products', [WorkspaceController::class, 'products'])->name('products.index');
Route::post('products', [WorkspaceController::class, 'storeProduct'])->name('products.store');

Route::get('inventory', [WorkspaceController::class, 'inventory'])->name('inventory.index');

Route::get('payments', [WorkspaceController::class, 'payments'])->name('payments.index');

// ── Shift Management ──
Route::get('shifts/current', [ShiftController::class, 'current'])->name('shifts.current');
Route::post('shifts/open', [ShiftController::class, 'open'])->name('shifts.open');
Route::post('shifts/{shift}/close', [ShiftController::class, 'close'])->name('shifts.close');

// ── Fallback Theme Routing (if exists) ──
Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
Route::get('{any}', [RoutingController::class, 'root'])->name('any');