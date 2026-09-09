<?php

use App\Http\Controllers\Web\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Default route: Login Page
| Protected route: /dashboard
|
*/

// Default landing page is the login page
Route::get('/', [AuthController::class, 'showLoginForm'])->name('home');

// Auth routes
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard for authenticated users
Route::middleware('auth')->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
