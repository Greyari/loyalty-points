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

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
