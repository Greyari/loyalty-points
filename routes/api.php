<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\DashboardController;

// ===================================================================
// PUBLIC ROUTES
// ===================================================================
Route::post('/login', [AuthController::class, 'login']);

// ===================================================================
// PROTECTED ROUTES
// ===================================================================
Route::middleware('auth:sanctum')->group(function () {

    // -------------------------------------------------------------------
    // AUTHENTICATION
    // -------------------------------------------------------------------
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // -------------------------------------------------------------------
    // PRODUCTS
    // -------------------------------------------------------------------
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/', [ProductController::class, 'store']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });

    // -------------------------------------------------------------------
    // CUSTOMERS
    // -------------------------------------------------------------------
    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerController::class, 'index']);
        Route::post('/', [CustomerController::class, 'store']);
        Route::get('/{id}', [CustomerController::class, 'show']);
        Route::put('/{id}', [CustomerController::class, 'update']);
        Route::delete('/{id}', [CustomerController::class, 'destroy']);

        // Extra routes untuk customer
        Route::get('/{id}/transactions', [CustomerController::class, 'transactions']);
        Route::get('/{id}/total-points', [CustomerController::class, 'totalPoints']);
    });

    // -------------------------------------------------------------------
    // TRANSACTIONS
    // -------------------------------------------------------------------
    Route::prefix('transactions')->group(function () {
        Route::get('/', [TransactionController::class, 'index']);
        Route::post('/', [TransactionController::class, 'store']);
        Route::get('/{id}', [TransactionController::class, 'show']);
        Route::delete('/{id}', [TransactionController::class, 'destroy']);
    });

    // -------------------------------------------------------------------
    // DASHBOARD
    // -------------------------------------------------------------------
    Route::prefix('dashboard')->group(function () {
        Route::get('/summary', [DashboardController::class, 'summary']);
        Route::get('/top-products', [DashboardController::class, 'topProducts']);
        Route::get('/top-customers', [DashboardController::class, 'topCustomers']);
        Route::get('/transaction-chart', [DashboardController::class, 'transactionChart']);
    });

});
