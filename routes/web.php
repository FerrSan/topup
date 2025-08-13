<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GameController as AdminGameController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\WebhookController as AdminWebhookController;
use App\Http\Controllers\Webhook\MidtransController;
use App\Http\Controllers\Webhook\XenditController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');

// Games
Route::prefix('games')->group(function () {
    Route::get('/', [GameController::class, 'index'])->name('games.index');
    Route::get('/search', [GameController::class, 'search'])->name('games.search');
    Route::get('/{slug}', [GameController::class, 'show'])->name('games.show');
});

// Invoice
Route::prefix('invoice')->group(function () {
    Route::get('/', [InvoiceController::class, 'check'])->name('invoice.check');
    Route::post('/search', [InvoiceController::class, 'search'])->name('invoice.search');
    Route::get('/{invoiceNo}', [InvoiceController::class, 'show'])->name('invoice.show');
});

// Checkout
Route::post('/checkout', [CheckoutController::class, 'process'])
    ->middleware('throttle:checkout')
    ->name('checkout.process');
Route::post('/validate-coupon', [CheckoutController::class, 'validateCoupon'])->name('coupon.validate');

// Testimonial
Route::get('/testimonial/create/{orderId}', [TestimonialController::class, 'create'])->name('testimonial.create');
Route::post('/testimonial/{orderId}', [TestimonialController::class, 'store'])->name('testimonial.store');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
    
    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);
    
    Route::get('forgot-password', [ForgotPasswordController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
    
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', [VerifyEmailController::class, '__invoke'])->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', [VerifyEmailController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [VerifyEmailController::class, 'send'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
    
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
});

// Authenticated User Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::get('/orders', [ProfileController::class, 'orders'])->name('profile.orders');
        Route::get('/testimonials', [ProfileController::class, 'testimonials'])->name('profile.testimonials');
    });
});

// Admin Routes
Route::middleware(['auth', 'verified', 'role:admin|super-admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    
    // Games Management
    Route::prefix('games')->group(function () {
        Route::get('/', [AdminGameController::class, 'index'])->name('admin.games.index');
        Route::get('/create', [AdminGameController::class, 'create'])->name('admin.games.create');
        Route::post('/', [AdminGameController::class, 'store'])->name('admin.games.store');
        Route::get('/{game}/edit', [AdminGameController::class, 'edit'])->name('admin.games.edit');
        Route::put('/{game}', [AdminGameController::class, 'update'])->name('admin.games.update');
        Route::delete('/{game}', [AdminGameController::class, 'destroy'])->name('admin.games.destroy');
    });
    
    // Orders Management
    Route::prefix('orders')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
        Route::post('/{order}/refund', [AdminOrderController::class, 'refund'])->name('admin.orders.refund');
    });
    
    // Products Management
    Route::prefix('products')->group(function () {
        Route::get('/', [AdminProductController::class, 'index'])->name('admin.products.index');
        Route::get('/create', [AdminProductController::class, 'create'])->name('admin.products.create');
        Route::post('/', [AdminProductController::class, 'store'])->name('admin.products.store');
        Route::get('/{product}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
        Route::put('/{product}', [AdminProductController::class, 'update'])->name('admin.products.update');
        Route::delete('/{product}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');
    });
    
    // Webhooks Log
    Route::prefix('webhooks')->group(function () {
        Route::get('/', [AdminWebhookController::class, 'index'])->name('admin.webhooks.index');
    });
});

// Webhook routes (no CSRF)
Route::middleware(['api', 'throttle:webhook'])->prefix('webhooks')->group(function () {
    Route::post('/midtrans', [MidtransController::class, 'handle'])
        ->middleware('webhook.signature:midtrans')
        ->name('webhook.midtrans');
    
    Route::post('/xendit', [XenditController::class, 'handle'])
        ->middleware('webhook.signature:xendit')
        ->name('webhook.xendit');
});

// Fallback route - must be last
Route::fallback(function () {
    return redirect()->route('home');
});
