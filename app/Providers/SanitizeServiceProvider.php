<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SanitizeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        include_once app_path() . '/Helpers/Sanitize.php';
    }
}
