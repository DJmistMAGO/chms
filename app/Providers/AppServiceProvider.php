<?php

namespace App\Providers;

use App\Http\Middleware\LogoutIfDeactivated;
use App\Models\Booking;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
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
        URL::forceScheme('https');
        // Register middleware to log out users who were deactivated by admin
        if ($this->app->bound('router')) {
            $router = $this->app->make('router');
            // Add to the 'web' middleware group so it runs on authenticated web requests
            $router->pushMiddlewareToGroup('web', LogoutIfDeactivated::class);
        }

        View::composer('layouts.authenticated.sidebar', function ($view) {
            $view->with('newReservationsCount', Booking::where('status', 'Pending')->count());
        });
    }
}
