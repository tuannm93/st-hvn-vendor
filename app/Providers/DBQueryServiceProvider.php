<?php

namespace App\Providers;

use App\Services\DBQuery;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class DBQueryServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var boolean
     */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton(
            DBQuery::class,
            function () {
                return new DBQuery();
            }
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [DBQuery::class];
    }
}
