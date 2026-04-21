<?php

namespace Savitriya\Icici_upi;

use Illuminate\Support\ServiceProvider;

class IciciUpiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Configuration
        $this->publishes([
            __DIR__.'/../config/icici_upi.php' => config_path('icici_upi.php'),
        ]);

        // routes
        if (! $this->app->routesAreCached()) {
            require __DIR__ . '/routes.php';
        }

        // Database migration
        $this->publishes([
            __DIR__.'/../migrations/2016_11_19_160202_create_icici_upi_transaction_tables.php' => database_path('migrations/2016_11_19_160202_create_icici_upi_transaction_tables.php'),
        ]);

        // Keys
        $this->publishes([
            __DIR__.'/../config/upi-keys' => config_path('upi-keys'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
