<?php

namespace PiyushJaiswal;

use Illuminate\Support\ServiceProvider;

class PaysprintServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Automatically publish the config when the package is installed
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/paysprint.php' => config_path('paysprint.php'),
            ], 'config');
        }
    }

    public function register()
    {
        // Merge the configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../config/paysprint.php', 'paysprint'
        );
    }
}
