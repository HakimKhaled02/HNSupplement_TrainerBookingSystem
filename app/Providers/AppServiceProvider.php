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
        // Register the 'files' binding early to prevent issues with service providers
        // that need it before FilesystemServiceProvider is booted
        if (!$this->app->bound('files')) {
            $this->app->singleton('files', function () {
                return new \Illuminate\Filesystem\Filesystem;
            });
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

