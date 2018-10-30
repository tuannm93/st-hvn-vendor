<?php

namespace App\Providers\Demand;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class DemandServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend(
            'test',
            function ($attribute, $value, $parameters, $validator) {
                return $value == 2;
            }
        );

        Validator::extend(
            'date_time_format',
            function ($attribute, $value, $parameters, $validator) {
                return preg_match($value, '');
            }
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
