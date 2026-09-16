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
Route::post('rooms', [WorkspaceController::class, 'storeRoom'])->name('rooms.store');
Route::put('rooms/{room}', [WorkspaceController::class, 'updateRoom'])->name('rooms.update');
Route::delete('rooms/{room}', [WorkspaceController::class, 'destroyRoom'])->name('rooms.destroy');

Route::get('bookings', [WorkspaceController::class, 'bookings'])->name('bookings.index');
Route::post('bookings', [WorkspaceController::class, 'storeBooking'])->name('bookings.store');
Route::put('bookings/{booking}', [WorkspaceController::class, 'updateBooking'])->name('bookings.update');
Route::delete('bookings/{booking}', [WorkspaceController::class, 'destroyBooking'])->name('bookings.destroy');
Route::post('bookings/{booking}/check-in', [WorkspaceController::class, 'checkInBooking'])->name('bookings.check-in');

Route::get('deals/active', [WorkspaceController::class, 'activeDeals'])->name('deals.active');

Route::get('products', [WorkspaceController::class, 'products'])->name('products.index');
Route::post('products', [WorkspaceController::class, 'storeProduct'])->name('products.store');
Route::put('products/{product}', [WorkspaceController::class, 'updateProduct'])->name('products.update');
Route::delete('products/{product}', [WorkspaceController::class, 'destroyProduct'])->name('products.destroy');
Route::post('products/{product}/ingredients', [WorkspaceController::class, 'saveProductIngredients'])->name('products.ingredients.save');

Route::post('raw-materials', [WorkspaceController::class, 'storeRawMaterial'])->name('raw-materials.store');
Route::put('raw-materials/{rawMaterial}', [WorkspaceController::class, 'updateRawMaterial'])->name('raw-materials.update');
Route::post('raw-materials/{rawMaterial}/add-stock', [WorkspaceController::class, 'addStockRawMaterial'])->name('raw-materials.add-stock');
Route::delete('raw-materials/{rawMaterial}', [WorkspaceController::class, 'destroyRawMaterial'])->name('raw-materials.destroy');

Route::get('inventory', [WorkspaceController::class, 'inventory'])->name('inventory.index');

Route::get('payments', [WorkspaceController::class, 'payments'])->name('payments.index');

// ── Shift Management ──
Route::get('shifts/current', [ShiftController::class, 'current'])->name('shifts.current');
Route::post('shifts/open', [ShiftController::class, 'open'])->name('shifts.open');
Route::post('shifts/{shift}/close', [ShiftController::class, 'close'])->name('shifts.close');

// ── Cashier Portal Orders & Customer History API ──
Route::get('cashier/portal-orders', [\App\Http\Controllers\CashierController::class, 'getPortalOrders'])->name('cashier.portal-orders');
Route::post('cashier/orders/{order}/fulfillment', [\App\Http\Controllers\CashierController::class, 'updateFulfillmentStatus'])->name('cashier.orders.fulfillment');
Route::get('cashier/customers/{customer}/history', [\App\Http\Controllers\CashierController::class, 'getCustomerHistory'])->name('cashier.customers.history');
Route::post('cashier/customers/{customer}/notes', [\App\Http\Controllers\CashierController::class, 'updateCustomerNotes'])->name('cashier.customers.notes');

// ── Settings Save Route ──
Route::post('settings/general', function (\Illuminate\Http\Request $request) {
    foreach ($request->except('_token') as $key => $val) {
        \App\Models\Setting::set($key, $val, is_bool($val) ? 'boolean' : 'string', 'general');
    }
    return back()->with('success', 'تم حفظ الإعدادات بنجاح');
})->name('settings.general.save');

// ── HubSpot CRM Integration Routes ──
Route::prefix('hubspot')->name('admin.hubspot.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\HubSpotController::class, 'index'])->name('index');
    Route::post('settings', [\App\Http\Controllers\Admin\HubSpotController::class, 'updateSettings'])->name('settings.update');
    Route::post('test', [\App\Http\Controllers\Admin\HubSpotController::class, 'testConnection'])->name('test');
    Route::post('sync', [\App\Http\Controllers\Admin\HubSpotController::class, 'sync'])->name('sync');
    Route::post('pull', [\App\Http\Controllers\Admin\HubSpotController::class, 'pull'])->name('pull');
    Route::get('report', [\App\Http\Controllers\Admin\HubSpotController::class, 'report'])->name('report');
});

// ── Admin Events & Community Routes ──
Route::prefix('admin/events')->name('admin.events.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\EventController::class, 'index'])->name('index');
    Route::post('/', [\App\Http\Controllers\Admin\EventController::class, 'store'])->name('store');
    Route::put('{event}', [\App\Http\Controllers\Admin\EventController::class, 'update'])->name('update');
    Route::post('{event}/toggle', [\App\Http\Controllers\Admin\EventController::class, 'toggleActive'])->name('toggle');
    Route::delete('{event}', [\App\Http\Controllers\Admin\EventController::class, 'destroy'])->name('destroy');
});
Route::get('events', fn() => redirect()->route('admin.events.index'));

// ── Referral Redirect ──
Route::get('ref/{code}', function ($code) {
    return redirect()->route('portal.register', ['ref' => $code]);
})->name('portal.referral');

// ── Customer Portal Routes ──
Route::prefix('app')->group(function () {
    // Guest customer routes
    Route::get('login', [\App\Http\Controllers\Customer\PortalAuthController::class, 'showLogin'])->name('portal.login');
    Route::post('login', [\App\Http\Controllers\Customer\PortalAuthController::class, 'login'])->name('portal.login.submit');
    Route::get('register', [\App\Http\Controllers\Customer\PortalAuthController::class, 'showRegister'])->name('portal.register');
    Route::post('register', [\App\Http\Controllers\Customer\PortalAuthController::class, 'register'])->name('portal.register.submit');
    Route::post('logout', [\App\Http\Controllers\Customer\PortalAuthController::class, 'logout'])->name('portal.logout');

    // Authenticated customer routes
    Route::middleware('auth:customer')->group(function () {
        Route::get('/', [\App\Http\Controllers\Customer\PortalController::class, 'home'])->name('portal.home');
        Route::get('menu', [\App\Http\Controllers\Customer\PortalController::class, 'menu'])->name('portal.menu');
        Route::post('orders', [\App\Http\Controllers\Customer\PortalController::class, 'storeOrder'])->name('portal.orders.store');
        Route::get('orders', [\App\Http\Controllers\Customer\PortalController::class, 'orders'])->name('portal.orders');
        Route::get('community', [\App\Http\Controllers\Customer\PortalController::class, 'community'])->name('portal.community');
        Route::get('loyalty', [\App\Http\Controllers\Customer\PortalController::class, 'loyalty'])->name('portal.loyalty');
        Route::get('profile', [\App\Http\Controllers\Customer\PortalController::class, 'profile'])->name('portal.profile');
        Route::post('profile', [\App\Http\Controllers\Customer\PortalController::class, 'updateProfile'])->name('portal.profile.update');
    });
});

// ── Fallback Theme Routing (if exists) ──
Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
Route::get('{any}', [RoutingController::class, 'root'])->name('any');