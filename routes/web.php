<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InventoryLogController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'processLogin']);
Route::post('/logout', [AuthController::class, 'logout']);


// GRUP 1: KHUSUS ADMIN SAJA! (Dipasang gembok ganda: 'auth' dan 'role:admin')
Route::middleware(['auth', 'role:admin'])->group(function () {
    // 1. Dashboard
    Route::get('/', [App\Http\Controllers\DashboardController::class, 'index']);
    
    // 2. Semua Route Kategori
    Route::get('/kategori', [App\Http\Controllers\CategoryController::class, 'index']);
    Route::get('/kategori/tambah', [App\Http\Controllers\CategoryController::class, 'create']);
    Route::post('/kategori', [App\Http\Controllers\CategoryController::class, 'store']);
    Route::get('/kategori/{id}/edit', [App\Http\Controllers\CategoryController::class, 'edit']);
    Route::put('/kategori/{id}', [App\Http\Controllers\CategoryController::class, 'update']);
    Route::delete('/kategori/{id}', [App\Http\Controllers\CategoryController::class, 'destroy']);
    
    // 3. Semua Route Produk
    Route::get('/produk', [App\Http\Controllers\ProductController::class,'index']);
    Route::get('/produk/tambah', [App\Http\Controllers\ProductController::class,'create']);
    Route::post('/produk', [App\Http\Controllers\ProductController::class, 'store']);
    Route::get('/produk/{id}/edit', [ProductController::class, 'edit']);
    Route::put('/produk/{id}', [ProductController::class, 'update']);
    Route::delete('/produk/{id}', [ProductController::class, 'destroy']);

    Route::get('/inventori', [InventoryLogController::class, 'index']);
    Route::post('/inventori', [InventoryLogController::class, 'store']);

    Route::get('/buku-kas', [CashFlowController::class, 'index']);
    Route::post('/buku-kas', [CashFlowController::class, 'store']);

});
// GRUP 2: UMUM (Admin ATAU Kasir boleh masuk asal sudah 'auth' / Login)
Route::middleware(['auth'])->group(function () {
    // Transaksi Kasir
    Route::get('/transaksi/baru', [App\Http\Controllers\TransactionController::class, 'create']);
    Route::post('/transaksi', [App\Http\Controllers\TransactionController::class, 'store']);
    Route::get('/transaksi', [App\Http\Controllers\TransactionController::class, 'index']);
    Route::get('/transaksi/{id}', [App\Http\Controllers\TransactionController::class, 'show']);
    Route::get('/transaksi/cetak/{id}', [TransactionController::class, 'print']); // Rute cetak struk
    Route::delete('/transaksi/{id}', [App\Http\Controllers\TransactionController::class, 'destroy']);
    Route::get('/transaksi/laporan/excel', [App\Http\Controllers\TransactionController::class, 'exportExcel']);

});
