<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\DashboardController;
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
require __DIR__.'/auth.php';

// Dashboard route
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard route for regular users
    Route::get('/dashboard', function () {
        $user = auth()->user();
        
        // Get user statistics
        $stats = [
            'totalOrders' => $user->orders()->count(),
            'successOrders' => $user->orders()->where('status', 'SUCCESS')->count(),
            'totalSpent' => $user->orders()->where('status', 'SUCCESS')->sum('grand_total'),
        ];
        
        // Get recent orders
        $recentOrders = $user->orders()
            ->with(['game', 'product'])
            ->latest()
            ->limit(5)
            ->get();
        
        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
        ]);
    })->name('dashboard');
    
    // Profile routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        Route::get('/orders', [ProfileController::class, 'orders'])->name('profile.orders');
        Route::get('/testimonials', [ProfileController::class, 'testimonials'])->name('profile.testimonials');
    });
});

// Fallback route - must be last
Route::fallback(function () {
    return redirect()->route('home');
});