<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\ProductController;

Route::get('/', function () {
    $products = \App\Models\Product::orderBy('duration_days', 'asc')->take(4)->get();
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'products' => $products
    ]);
});

use App\Http\Controllers\VoucherController;
use App\Http\Controllers\AktivasiVoucherController;

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/users', [VoucherController::class, 'index'])->name('users');
    Route::post('/users/bulk', [VoucherController::class, 'bulkStore'])->name('users.bulk_store');
    Route::post('/users', [VoucherController::class, 'store'])->name('users.store');
    Route::put('/users/{voucher}', [VoucherController::class, 'update'])->name('users.update');
    Route::delete('/users/{voucher}', [VoucherController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{voucher}/activate', [VoucherController::class, 'manualActivate'])->name('users.manual_activate');
    Route::post('/users/{voucher}/block', [VoucherController::class, 'manualBlock'])->name('users.manual_block');

    Route::get('/produk', [ProductController::class, 'index'])->name('products');
    Route::post('/produk', [ProductController::class, 'store'])->name('products.store');
    Route::put('/produk/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/produk/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/aktivasi-voucher', [AktivasiVoucherController::class, 'index'])->name('aktivasi.index');
Route::post('/aktivasi-voucher', [AktivasiVoucherController::class, 'activate'])->name('aktivasi.activate');
