<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// ================================
// GUEST ROUTES (belum login)
// ================================
Route::middleware('guest')->group(function () {

    // Halaman login
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');

    // Proses login
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});


// ================================
// AUTH ROUTES (sudah login)
// ================================
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('home.dashboard');
    })->name('dashboard');

    // Customer
    Route::get('/customer', function () {
        return view('customer.customer_page');
    })->name('customer');

    // Transaction
    Route::get('/transaction', function () {
        return view('transaction.transaction_page');
    })->name('transaction');

    // Inventory
    Route::get('/inventory', function () {
        return view('inventory.inventory_page');
    })->name('inventory');

    // Settings
    Route::get('/settings', function () {
        return view('settings.settings_page');
    })->name('settings');
});
