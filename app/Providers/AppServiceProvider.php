<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\LogoutIfDeactivated;

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
    }
}
