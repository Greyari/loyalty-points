<?php

use Illuminate\Support\Facades\Route;

// Display login form
Route::get('/', function () {
    return view('auth.login');
})->name('login');
