<?php

// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\Api\ProductController;
// use App\Http\Controllers\Api\CustomerController;
// use App\Http\Controllers\Api\TransactionController;
// use App\Http\Controllers\Api\DashboardController;
// use App\Http\Controllers\Api\PointTransactionController;

// Route::middleware('auth')->group(function () {

//     // PRODUCTS
//     Route::prefix('products')->group(function () {
//         Route::get('/', [ProductController::class, 'index']);
//         Route::post('/', [ProductController::class, 'store']);
//         Route::get('/{id}', [ProductController::class, 'show']);
//         Route::put('/{id}', [ProductController::class, 'update']);
//         Route::delete('/{id}', [ProductController::class, 'destroy']);
//     });

//     // CUSTOMERS
//     Route::prefix('customers')->group(function () {
//         Route::get('/', [CustomerController::class, 'index']);
//         Route::post('/', [CustomerController::class, 'store']);
//         Route::get('/{id}', [CustomerController::class, 'show']);
//         Route::put('/{id}', [CustomerController::class, 'update']);
//         Route::delete('/{id}', [CustomerController::class, 'destroy']);
//     });

//     // TRANSACTIONS
//     Route::prefix('transactions')->group(function () {
//         Route::get('/', [PointTransactionController::class, 'index']);
//         Route::post('/', [PointTransactionController::class, 'store']);
//         Route::get('/{id}', [PointTransactionController::class, 'show']);
//         Route::delete('/{id}', [PointTransactionController::class, 'destroy']);
//     });

//     // DASHBOARD
//     Route::prefix('dashboard')->group(function () {
//         Route::get('/summary', [DashboardController::class, 'summary']);
//         Route::get('/top-products', [DashboardController::class, 'topProducts']);
//         Route::get('/top-customers', [DashboardController::class, 'topCustomers']);
//         Route::get('/transaction-chart', [DashboardController::class, 'transactionChart']);
//     });

// });
