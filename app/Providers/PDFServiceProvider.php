<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\PdfGenerator;

class PDFServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var boolean
     */
    protected $defer = true;

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
        $this->app->singleton(
            PdfGenerator::class,
            function () {
                return new PdfGenerator();
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
        return [PdfGenerator::class];
    }
}
