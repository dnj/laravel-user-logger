<?php

namespace dnj\UserLogger;

use dnj\UserLogger\Contracts\ILogger;
use Illuminate\Support\ServiceProvider as SupportServiceProvider;

class ServiceProvider extends SupportServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/user-logger.php', 'user-logger');
        $this->app->bind(ILogger::class, Logger::class);
    }

    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/user-logger.php' => config_path('user-logger.php'),
            ], 'config');
        }
    }
}
