<?php

use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\HubSpotController;
use App\Http\Controllers\Admin\StaffNotificationController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\Customer\PortalAuthController;
use App\Http\Controllers\Customer\PortalController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\WorkspaceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — DT-System Workspace Management
|--------------------------------------------------------------------------
| Clean, well-structured architecture with explicit controllers,
| no dynamic template wildcards, and zero closure dependencies for
| seamless route caching (php artisan route:cache).
|--------------------------------------------------------------------------
*/

// =========================================================================
// 1. Authentication & Staff Session Routes
// =========================================================================
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.submit');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// =========================================================================
// 2. Protected Staff & Admin Dashboard Routes (Requires Auth)
// =========================================================================
Route::middleware('auth')->group(function () {

    // ── Core Dashboard & Analytics ──
    Route::get('/', [RoutingController::class, 'root'])->name('root');
    Route::get('dashboard', [RoutingController::class, 'root'])->name('dashboard');
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

    // ── Customers ──
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [WorkspaceController::class, 'customers'])->name('index');
        Route::post('/', [WorkspaceController::class, 'storeCustomer'])->name('store');
        Route::post('import', [WorkspaceController::class, 'importCustomers'])->name('import');
        Route::get('sample-csv', [WorkspaceController::class, 'sampleCustomerCsv'])->name('sample');
        Route::put('{customer}', [WorkspaceController::class, 'updateCustomer'])->name('update');
    });

    // ── Rooms & Spaces ──
    Route::prefix('rooms')->name('rooms.')->group(function () {
        Route::get('/', [WorkspaceController::class, 'rooms'])->name('index');
        Route::post('/', [WorkspaceController::class, 'storeRoom'])->name('store');
        Route::put('{room}', [WorkspaceController::class, 'updateRoom'])->name('update');
        Route::delete('{room}', [WorkspaceController::class, 'destroyRoom'])->name('destroy');
    });

    // ── Bookings ──
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::get('/', [WorkspaceController::class, 'bookings'])->name('index');
        Route::post('/', [WorkspaceController::class, 'storeBooking'])->name('store');
        Route::put('{booking}', [WorkspaceController::class, 'updateBooking'])->name('update');
        Route::delete('{booking}', [WorkspaceController::class, 'destroyBooking'])->name('destroy');
        Route::post('{booking}/check-in', [WorkspaceController::class, 'checkInBooking'])->name('check-in');
    });

    // ── Active Sessions / Deals ──
    Route::get('deals/active', [WorkspaceController::class, 'activeDeals'])->name('deals.active');

    // ── Cafe Products & Recipes ──
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [WorkspaceController::class, 'products'])->name('index');
        Route::post('/', [WorkspaceController::class, 'storeProduct'])->name('store');
        Route::put('{product}', [WorkspaceController::class, 'updateProduct'])->name('update');
        Route::delete('{product}', [WorkspaceController::class, 'destroyProduct'])->name('destroy');
        Route::post('{product}/ingredients', [WorkspaceController::class, 'saveProductIngredients'])->name('ingredients.save');
    });

    // ── Raw Materials ──
    Route::prefix('raw-materials')->name('raw-materials.')->group(function () {
        Route::post('/', [WorkspaceController::class, 'storeRawMaterial'])->name('store');
        Route::put('{rawMaterial}', [WorkspaceController::class, 'updateRawMaterial'])->name('update');
        Route::post('{rawMaterial}/add-stock', [WorkspaceController::class, 'addStockRawMaterial'])->name('add-stock');
        Route::delete('{rawMaterial}', [WorkspaceController::class, 'destroyRawMaterial'])->name('destroy');
    });

    // ── Inventory Movement ──
    Route::get('inventory', [WorkspaceController::class, 'inventory'])->name('inventory.index');

    // ── Payments & Financial ──
    Route::get('payments', [WorkspaceController::class, 'payments'])->name('payments.index');

    // ── Shifts ──
    Route::prefix('shifts')->name('shifts.')->group(function () {
        Route::get('current', [ShiftController::class, 'current'])->name('current');
        Route::post('open', [ShiftController::class, 'open'])->name('open');
        Route::post('{shift}/close', [ShiftController::class, 'close'])->name('close');
    });

    // ── Cashier POS & Realtime APIs ──
    Route::get('cashier', [CashierController::class, 'index'])->name('cashier');
    Route::prefix('cashier')->name('cashier.')->group(function () {
        Route::get('portal-orders', [CashierController::class, 'getPortalOrders'])->name('portal-orders');
        Route::post('orders/{order}/fulfillment', [CashierController::class, 'updateFulfillmentStatus'])->name('orders.fulfillment');
        Route::get('customers/{customer}/history', [CashierController::class, 'getCustomerHistory'])->name('customers.history');
        Route::post('customers/{customer}/notes', [CashierController::class, 'updateCustomerNotes'])->name('customers.notes');

        // Alert / Reminder System
        Route::post('alerts', [CashierController::class, 'createAlert'])->name('alerts.create');
        Route::get('alerts/pending', [CashierController::class, 'checkPendingAlerts'])->name('alerts.pending');
        Route::post('alerts/{schedule}/complete', [CashierController::class, 'completeAlert'])->name('alerts.complete');
        Route::post('alerts/{schedule}/snooze', [CashierController::class, 'snoozeAlert'])->name('alerts.snooze');
    });

    // ── System Settings & Branding ──
    Route::get('settings/general', [SettingController::class, 'general'])->name('settings.general');
    Route::post('settings/general', [SettingController::class, 'saveGeneral'])->name('settings.general.save');

    // ── HubSpot CRM Integration ──
    Route::prefix('hubspot')->name('admin.hubspot.')->group(function () {
        Route::get('/', [HubSpotController::class, 'index'])->name('index');
        Route::post('settings', [HubSpotController::class, 'updateSettings'])->name('settings.update');
        Route::post('test', [HubSpotController::class, 'testConnection'])->name('test');
        Route::post('sync', [HubSpotController::class, 'sync'])->name('sync');
        Route::post('pull', [HubSpotController::class, 'pull'])->name('pull');
        Route::get('report', [HubSpotController::class, 'report'])->name('report');
    });

    // ── Team Members & Roles Management ──
    Route::prefix('admin/team')->name('admin.team.')->group(function () {
        Route::get('/', [TeamController::class, 'index'])->name('index');
        Route::post('users', [TeamController::class, 'storeUser'])->name('users.store');
        Route::put('users/{user}', [TeamController::class, 'updateUser'])->name('users.update');
        Route::delete('users/{user}', [TeamController::class, 'destroyUser'])->name('users.destroy');
        Route::post('roles', [TeamController::class, 'storeRole'])->name('roles.store');
        Route::put('roles/{role}', [TeamController::class, 'updateRole'])->name('roles.update');
        Route::delete('roles/{role}', [TeamController::class, 'destroyRole'])->name('roles.destroy');
    });

    // ── Community Events & Banners ──
    Route::prefix('admin/events')->name('admin.events.')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::post('/', [EventController::class, 'store'])->name('store');
        Route::put('{event}', [EventController::class, 'update'])->name('update');
        Route::post('{event}/toggle', [EventController::class, 'toggleActive'])->name('toggle');
        Route::post('{event}/toggle-featured', [EventController::class, 'toggleFeatured'])->name('toggle-featured');
        Route::delete('{event}', [EventController::class, 'destroy'])->name('destroy');
    });
    Route::get('events', [RoutingController::class, 'eventsRedirect']);

    // ── Staff Notifications System ──
    Route::prefix('admin/notifications')->name('admin.notifications.')->group(function () {
        Route::get('mine', [StaffNotificationController::class, 'mine'])->name('mine');
        Route::get('sent', [StaffNotificationController::class, 'sent'])->name('sent');
        Route::post('/', [StaffNotificationController::class, 'create'])->name('create');
        Route::post('{notification}/read', [StaffNotificationController::class, 'markRead'])->name('read');
        Route::delete('{notification}', [StaffNotificationController::class, 'destroy'])->name('destroy');
    });

    // Role Permissions Quick-Update
    Route::prefix('admin/permissions')->name('admin.permissions.')->group(function () {
        Route::get('{role}', [StaffNotificationController::class, 'getRolePermissions'])->name('show');
        Route::put('{role}', [StaffNotificationController::class, 'updateRolePermissions'])->name('update');
    });
});

// =========================================================================
// 8. Customer Smart Portal & Mobile Web App (/app)
// =========================================================================
Route::get('ref/{code}', [RoutingController::class, 'referralRedirect'])->name('portal.referral');

Route::prefix('app')->group(function () {
    // Guest customer routes
    Route::get('login', [PortalAuthController::class, 'showLogin'])->name('portal.login');
    Route::post('login', [PortalAuthController::class, 'login'])->name('portal.login.submit');
    Route::get('register', [PortalAuthController::class, 'showRegister'])->name('portal.register');
    Route::post('register', [PortalAuthController::class, 'register'])->name('portal.register.submit');
    Route::post('logout', [PortalAuthController::class, 'logout'])->name('portal.logout');

    // Authenticated customer routes
    Route::middleware('auth:customer')->group(function () {
        Route::get('/', [PortalController::class, 'home'])->name('portal.home');
        Route::get('menu', [PortalController::class, 'menu'])->name('portal.menu');
        Route::post('orders', [PortalController::class, 'storeOrder'])->name('portal.orders.store');
        Route::get('orders', [PortalController::class, 'orders'])->name('portal.orders');
        Route::get('community', [PortalController::class, 'community'])->name('portal.community');
        Route::get('loyalty', [PortalController::class, 'loyalty'])->name('portal.loyalty');
        Route::get('profile', [PortalController::class, 'profile'])->name('portal.profile');
        Route::post('profile', [PortalController::class, 'updateProfile'])->name('portal.profile.update');
    });
});

// =========================================================================
// 10. Standard Fallback Handler (Replaces legacy theme wildcard routes)
// =========================================================================
Route::fallback([RoutingController::class, 'notFound']);