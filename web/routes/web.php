<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\PendaftarController;

Route::get('/', function () {
    $products = \App\Models\Product::orderBy('duration_days', 'asc')->take(4)->get();
    $testimonials = \App\Models\Testimonial::latest()->get();
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'products' => $products,
        'testimonials' => $testimonials
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

    Route::get('/testimoni', [TestimonialController::class, 'index'])->name('testimonials');
    Route::post('/testimoni', [TestimonialController::class, 'store'])->name('testimonials.store');
    Route::put('/testimoni/{testimonial}', [TestimonialController::class, 'update'])->name('testimonials.update');
    Route::delete('/testimoni/{testimonial}', [TestimonialController::class, 'destroy'])->name('testimonials.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pendaftar Routes (Manage web users)
    Route::get('/pendaftar', [PendaftarController::class, 'index'])->name('pendaftar');
    Route::post('/pendaftar', [PendaftarController::class, 'store'])->name('pendaftar.store');
    Route::put('/pendaftar/{pendaftar}', [PendaftarController::class, 'update'])->name('pendaftar.update');
    Route::delete('/pendaftar/{pendaftar}', [PendaftarController::class, 'destroy'])->name('pendaftar.destroy');
});

require __DIR__.'/auth.php';

Route::get('/aktivasi-voucher', [AktivasiVoucherController::class, 'index'])->name('aktivasi.index');
Route::post('/aktivasi-voucher', [AktivasiVoucherController::class, 'activate'])->name('aktivasi.activate');
