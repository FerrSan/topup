<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        
        // Order Events
        \App\Events\OrderCreated::class => [
            \App\Listeners\LogOrderActivity::class,
        ],
        \App\Events\OrderPaid::class => [
            \App\Listeners\LogOrderActivity::class,
            \App\Listeners\SendOrderNotification::class,
        ],
        \App\Events\OrderProcessing::class => [
            \App\Listeners\LogOrderActivity::class,
        ],
        \App\Events\OrderCompleted::class => [
            \App\Listeners\LogOrderActivity::class,
            \App\Listeners\SendOrderNotification::class,
            \App\Listeners\UpdateGameStatistics::class,
        ],
        \App\Events\OrderFailed::class => [
            \App\Listeners\LogOrderActivity::class,
            \App\Listeners\SendOrderNotification::class,
        ],
        \App\Events\OrderRefunded::class => [
            \App\Listeners\LogOrderActivity::class,
            \App\Listeners\SendOrderNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
