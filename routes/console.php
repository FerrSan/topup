<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\CheckExpiredOrdersJob;
use App\Jobs\GenerateReportsJob;

// =======================
// Scheduled tasks (aman)
// =======================

// Pakai ::class (tidak instantiate saat bootstrap)
Schedule::job(CheckExpiredOrdersJob::class)->everyFiveMinutes();

// Hanya dijadwalkan kalau kelasnya memang ada
if (class_exists(\App\Jobs\CleanupOldLogsJob::class)) {
    Schedule::job(\App\Jobs\CleanupOldLogsJob::class)->daily();
}

Schedule::job(GenerateReportsJob::class)->dailyAt('00:00');

// Backup database
Schedule::command('backup:run')->dailyAt('02:00');
Schedule::command('backup:clean')->weekly();

// Clear expired sessions
Schedule::command('session:gc')->hourly();

// Horizon snapshot (opsional guard jika Horizon belum diinstall)
if (class_exists(\Laravel\Horizon\Horizon::class)) {
    Schedule::command('horizon:snapshot')->everyFiveMinutes();
}

// =======================
// Custom commands
// =======================
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('topup:retry-failed', function () {
    $this->info('Retrying failed topups...');

    // Guard ProcessTopupJob agar tidak fatal kalau belum ada
    if (!class_exists(\App\Jobs\ProcessTopupJob::class)) {
        $this->error('ProcessTopupJob belum ada. Buat dulu: php artisan make:job ProcessTopupJob');
        return;
    }

    $failedOrders = \App\Models\Order::where('status', 'FAILED')
        ->where('retry_count', '<', 5)
        ->where('created_at', '>', now()->subHours(24))
        ->get();

    foreach ($failedOrders as $order) {
        \App\Jobs\ProcessTopupJob::dispatch($order);
        $this->info("Retrying order: {$order->invoice_no}");
    }

    $this->info("Retried {$failedOrders->count()} orders");
})->purpose('Retry failed topup orders');

Artisan::command('cache:warm', function () {
    $this->info('Warming up cache...');

    // Cache games
    if (class_exists(\App\Models\Game::class)) {
        \App\Models\Game::active()->get()->each(function ($game) {
            cache()->remember("game:{$game->slug}", 3600, fn() => $game);
        });
    }

    // Cache settings
    if (class_exists(\App\Models\Setting::class)) {
        \App\Models\Setting::all(); // get('*') biasanya bukan yang kamu mau
    }

    $this->info('Cache warmed up successfully');
})->purpose('Warm up application cache');
