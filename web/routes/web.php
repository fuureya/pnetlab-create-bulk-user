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
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\TransactionController;

Route::post('/midtrans/callback', [MidtransWebhookController::class, 'callback']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
        $user = $request->user();
        if ($user->role === 'admin') {
            $recentUsers = \App\Models\Voucher::with('user')->latest()->take(5)->get();
            return Inertia::render('Dashboard', [
                'recent_users' => $recentUsers
            ]);
        }

        $totalTransactions = $user->transactions()->count();
        $activeVouchers = $user->vouchers()->where('status', 'aktif')->count();
        $expiredVouchers = $user->vouchers()->where('status', 'expired')->count();
        $userVouchers = $user->vouchers()->latest()->get();

        return Inertia::render('Dashboard', [
            'stats' => [
                'total_transactions' => $totalTransactions,
                'active_vouchers' => $activeVouchers,
                'expired_vouchers' => $expiredVouchers
            ],
            'user_vouchers' => $userVouchers
        ]);
    })->name('dashboard');

    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/riwayat-transaksi', function (\Illuminate\Http\Request $request) {
        $transactions = $request->user()->transactions()->with('product')->latest()->get();
        return Inertia::render('UserTransaksi', [
            'transactions' => $transactions
        ]);
    })->name('riwayat-transaksi');

    Route::get('/aktivasi-voucher', [AktivasiVoucherController::class, 'index'])->name('aktivasi.index');
    Route::post('/aktivasi-voucher', [AktivasiVoucherController::class, 'activate'])->name('aktivasi.activate');
});

Route::middleware(['auth', 'verified', 'isAdmin'])->group(function () {
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

    Route::get('/transaksi', [TransactionController::class, 'index'])->name('transactions');
    
    // Pendaftar Routes (Manage web users)
    Route::get('/pendaftar', [PendaftarController::class, 'index'])->name('pendaftar');
    Route::post('/pendaftar', [PendaftarController::class, 'store'])->name('pendaftar.store');
    Route::put('/pendaftar/{pendaftar}', [PendaftarController::class, 'update'])->name('pendaftar.update');
    Route::delete('/pendaftar/{pendaftar}', [PendaftarController::class, 'destroy'])->name('pendaftar.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
