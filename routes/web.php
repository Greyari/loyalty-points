<?php

use Illuminate\Support\Facades\Route;

// Display login form
Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::get('/dashboard', function () {
    return view('home.dashboard');
})->name('dashboard');

Route::get('/customer', function () {
    return view('customer.customer_page');
})->name('customer');

Route::get('/transaction', function () {
    return view('transaction.transaction_page');
})->name('transaction');

Route::get('/inventory', function () {
    return view('inventory.inventory_page');
})->name('inventory');

Route::get('/settings', function () {
    return view('settings.settings_page');
})->name('settings');
