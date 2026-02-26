<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set default pagination
        \Illuminate\Pagination\Paginator::useBootstrapFive();
        
        // Register macro for currency formatting
        \Illuminate\Support\Collection::macro('toCurrencySAR', function () {
            return $this->map(fn ($value) => number_format($value, 2) . ' ر.س');
        });
    }
}
