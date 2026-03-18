<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\KasirController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingPageController::class, 'getIndex']);

/*
|--------------------------------------------------------------------------
| Admin Auth System
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login']);
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Admin Panel (Protected by Admin Guard)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:admin')->prefix('admin')->group(function () {

    // Dashboard & Laporan
    Route::get('/dashboard', [AdminController::class, 'getDashboard'])->name('admin.dashboard');
    Route::get('/laporan', [AdminController::class, 'getLaporan'])->name('admin.laporan');

    // Product Management
    Route::prefix('product')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/create', [ProductController::class, 'create']);
        Route::post('/store', [ProductController::class, 'store']);
        Route::get('/edit/{id}', [ProductController::class, 'edit']);
        Route::put('/update/{id}', [ProductController::class, 'update']);
        Route::delete('/delete/{id}', [ProductController::class, 'destroy']);
    });

    // Category Management
    Route::prefix('category')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::get('/create', [CategoryController::class, 'create']);
        Route::post('/store', [CategoryController::class, 'store']);
        Route::get('/edit/{id}', [CategoryController::class, 'edit']);
        Route::put('/update/{id}', [CategoryController::class, 'update']);
        Route::delete('/delete/{id}', [CategoryController::class, 'destroy']);
    });

    // Kasir System
    Route::prefix('kasir')->group(function () {
        Route::get('/', [KasirController::class, 'index'])->name('kasir.index');
        Route::get('/search', [KasirController::class, 'search'])->name('kasir.search');
        Route::post('/store', [KasirController::class, 'store'])->name('kasir.store');
        Route::get('/print/{id}', [KasirController::class, 'printStruk'])->name('kasir.print');
        Route::get('/history', [KasirController::class, 'history'])->name('kasir.history');
        Route::get('/kasir/history', [KasirController::class, 'history'])->name('kasir.history');

        // Hold Sales logic
        Route::post('/hold', [KasirController::class, 'hold'])->name('kasir.hold');
        Route::get('/hold-list', [KasirController::class, 'getHoldList'])->name('kasir.hold-list');
        Route::delete('/hold/{id}', [KasirController::class, 'destroyHold'])->name('kasir.hold-delete');
    });
});
