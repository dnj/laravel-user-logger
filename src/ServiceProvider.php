<?php

namespace dnj\UserLogger;

use dnj\UserLogger\Contracts\ILog;
use dnj\UserLogger\Contracts\ILogger;
use dnj\UserLogger\Policies\LogPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider as SupportServiceProvider;

class ServiceProvider extends SupportServiceProvider
{
    /**
     * @var array<class-string,class-string>
     */
    protected $policies = [
        ILog::class => LogPolicy::class,
    ];

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/user-logger.php', 'user-logger');
        $this->app->bind(ILogger::class, Logger::class);
    }

    public function boot()
    {
        $this->registerPublishes();
        $this->registerPolicies();
        $this->registerRoutes();
    }

    protected function registerPublishes(): void
    {
        if ($this->app->runningInConsole()) {
            if (config('user-logger.migrations.enable')) {
                $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
            }

            $this->publishes([
                __DIR__.'/../config/user-logger.php' => config_path('user-logger.php'),
            ], ['user-logger', 'user-logger-config', 'config']);
        }
    }

    protected function registerRoutes(): void
    {
        if (!config('user-logger.routes.enable')) {
            return;
        }
        $prefix = config('user-logger.routes.prefix', 'api/user-logger');
        Route::prefix($prefix)->name('user-logger.')->group(function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        });
    }

    protected function registerPolicies(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
