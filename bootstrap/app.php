<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Daftarkan alias middleware di sini
        $middleware->alias([
            'track.referral'     => \App\Http\Middleware\TrackReferral::class,
            'check.banned'       => \App\Http\Middleware\CheckBannedUser::class,
            'order.owner'        => \App\Http\Middleware\EnsureOrderBelongsToUser::class,
            'webhook.signature'  => \App\Http\Middleware\VerifyWebhookSignature::class,
            'throttle.checkout'  => \App\Http\Middleware\ThrottleCheckout::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
