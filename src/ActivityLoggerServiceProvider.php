<?php

namespace Kazitoha\ActivityLogger;

use Illuminate\Support\ServiceProvider;

class ActivityLoggerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/activity-logger.php', 'activity-logger');
    }

    public function boot(): void
    {
        // Publish config
        $this->publishes([
            __DIR__.'/../config/activity-logger.php' => config_path('activity-logger.php'),
        ], 'activity-logger-config');

        // Load migrations directly from the package
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
