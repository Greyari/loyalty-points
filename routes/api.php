<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\PointTransactionController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\AuthController;


Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {

    // AUTH
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // PRODUCTS (CRUD)
    Route::apiResource('products', ProductController::class);

    // CUSTOMERS (CRUD)
    Route::apiResource('customers', CustomerController::class);

    // POINT TRANSACTIONS
    Route::post('transactions', [PointTransactionController::class, 'store']);
    Route::get('transactions', [PointTransactionController::class, 'index']);
    Route::get('transactions/customer/{id}', [PointTransactionController::class, 'byCustomer']);

    // DASHBOARD
    Route::get('dashboard/top-products', [DashboardController::class, 'topProducts']);
    Route::get('dashboard/top-customers', [DashboardController::class, 'topCustomers']);
    Route::get('dashboard/summary', [DashboardController::class, 'summary']);
});
