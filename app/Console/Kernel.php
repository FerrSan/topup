<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Check expired orders every 5 minutes
        $schedule->job(new \App\Jobs\CheckExpiredOrdersJob)
            ->everyFiveMinutes()
            ->withoutOverlapping();
        
        // Clean up old logs daily
        $schedule->command('telescope:prune --hours=48')
            ->daily();
        
        // Backup database daily at 2 AM
        $schedule->command('backup:run --only-db')
            ->dailyAt('02:00');
        
        // Clean old backups weekly
        $schedule->command('backup:clean')
            ->weekly();
        
        // Generate daily reports
        $schedule->command('reports:generate daily')
            ->dailyAt('00:00');
        
        // Clear expired sessions
        $schedule->command('session:gc')
            ->hourly();
        
        // Horizon metrics
        $schedule->command('horizon:snapshot')
            ->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}