<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;

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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/data', [DashboardController::class, 'data'])->name('dashboard.data');
    Route::get('/chart-data', [DashboardController::class, 'chartData']);

    // Order Routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // Inventory
    Route::get('/inventory', [ProductController::class, 'index'])->name('inventory.index');
    Route::post('/inventory', [ProductController::class, 'store'])->name('inventory.store');
    Route::put('/inventory/{id}', [ProductController::class, 'update'])->name('inventory.update');
    Route::delete('/inventory/{id}', [ProductController::class, 'destroy'])->name('inventory.destroy');

    // Customer
    Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
    Route::post('/customer', [CustomerController::class, 'store'])->name('customer.store');
    Route::put('/customer/{id}', [CustomerController::class, 'update'])->name('customer.update');
    Route::delete('/customer/{id}', [CustomerController::class, 'destroy'])->name('customer.destroy');

    // Chart
    Route::get('/chart-data', [DashboardController::class, 'chartData'])->name('chart.data');
    Route::get('/chart-data-years', [DashboardController::class, 'getAvailableYears'])->name('chart.years');
    // Transaction
    // route::get('/transaction', [PointTransactionController::class, 'index'])->name('transaction.index');
    // Route::post('/transaction', [PointTransactionController::class, 'store'])->name('transaction.store');
    // Route::put('/transaction/{id}', [PointTransactionController::class, 'update'])->name('transaction.update');
    // Route::delete('/transaction/{id}', [PointTransactionController::class, 'destroy'])->name('transaction.destroy');

    // History
    Route::get('/log', function () {
        return view('log.log_activity');
    })->name('log');
    // User 
    Route::get('/user', function () {
        return view('user.user');
    })->name('user');
});
