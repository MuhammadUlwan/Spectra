<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\DompetController;
use App\Http\Controllers\TarikTunaiController;

// Redirect root ke login
Route::get('/', fn() => redirect()->route('login'));

// =======================
// Auth Routes
// =======================
Route::controller(AuthController::class)->group(function () {
    // pastikan Auth/Login.vue ada
    Route::get('/login', fn() => Inertia::render('Auth/Login'))->name('login');
    Route::post('/login', 'login');

    // pastikan Auth/Register.vue ada
    Route::get('/register', fn() => Inertia::render('Auth/Register'))->name('register');
    Route::post('/register', 'register');

    Route::post('/logout', 'logout')->name('logout');
});

// =======================
// Protected Routes
// =======================
Route::middleware(['auth'])->group(function () {
    // Dashboard investor/konsultan
    Route::get('/dashboard', fn() => Inertia::render('Dashboard'))->name('dashboard');

    // Profile
    Route::get('/profil', fn() => Inertia::render('Profile'))->name('profil');

    // Dashboard admin
    Route::get('/admin/dashboard', fn() => Inertia::render('Admin/Dashboard'))->name('admin.dashboard');

    // Deposit
    Route::controller(DepositController::class)->group(function () {
        Route::get('/deposit', 'index')->name('deposit.index');
        Route::post('/deposit', 'store')->name('deposit.store');
    });

    // Dompet
    Route::get('/dompet', [DompetController::class, 'index'])->name('dompet.index');

    // Tarik Tunai
    Route::controller(TarikTunaiController::class)->group(function () {
        Route::get('/tarik-tunai', 'index')->name('tarik-tunai.index');
        Route::post('/tarik-tunai', 'store')->name('tarik-tunai.store');
    });
});
