<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Broadcasting\MultiBroadcaster;
use Illuminate\Support\Facades\Broadcast;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Broadcast::extend('multi', function ($app) {
            return new MultiBroadcaster();
        });
        
        Paginator::useBootstrapFive();
    }
}