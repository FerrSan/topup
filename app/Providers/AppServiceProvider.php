<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Telescope only in local environment
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fix for MySQL
        Schema::defaultStringLength(191);
        
        // Use Bootstrap for pagination
        Paginator::useBootstrapFive();
        
        // Blade directives
        Blade::directive('currency', function ($expression) {
            return "<?php echo formatCurrency($expression); ?>";
        });
        
        Blade::directive('status', function ($expression) {
            return "<?php echo getStatusColor($expression); ?>";
        });
        
        // Gate definitions
        Gate::before(function ($user, $ability) {
            return $user->hasRole('super-admin') ? true : null;
        });
    }
}