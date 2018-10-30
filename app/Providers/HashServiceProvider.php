<?php
/**
 * Created by PhpStorm.
 * User: nguyentran
 * Date: 2/6/2018
 * Time: 12:28 PM
 */

namespace App\Providers;

use App\Services\Cake255Hash;
use Illuminate\Support\ServiceProvider;

class HashServiceProvider extends ServiceProvider
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
            'hash',
            function () {
                return new Cake255Hash(config('cake.salt'));
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
        return ['hash'];
    }
}
