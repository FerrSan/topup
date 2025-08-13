<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PlayerValidationController;
use App\Http\Controllers\Api\GameApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\OrderApiController;

Route::middleware(['api', 'throttle:api'])->group(function () {
    // Public API
    Route::get('/games', [GameApiController::class, 'index']);
    Route::get('/games/{slug}', [GameApiController::class, 'show']);
    Route::get('/games/{slug}/products', [ProductApiController::class, 'index']);
    Route::post('/validate-player', [PlayerValidationController::class, 'validate']);
    
    // Authenticated API
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/user', fn(Request $request) => $request->user());
        Route::get('/orders', [OrderApiController::class, 'index']);
        Route::get('/orders/{invoiceNo}', [OrderApiController::class, 'show']);
        Route::post('/orders', [OrderApiController::class, 'create']);
    });
    
    // Partner API (with API key)
    Route::middleware(['validate.api.key'])->prefix('partner')->group(function () {
        Route::post('/topup', [PartnerApiController::class, 'topup']);
        Route::get('/status/{referenceId}', [PartnerApiController::class, 'status']);
        Route::get('/balance', [PartnerApiController::class, 'balance']);
    });
});