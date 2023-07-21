<?php

namespace TianRosandhy\LaravelFonnte\Providers;

use Illuminate\Support\ServiceProvider;
use TianRosandhy\LaravelFonnte\Fonnte;

class FonnteServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/fonnte.php' => config_path('fonnte.php')
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $app = $this->app;

        // merge default config
        $this->mergeConfigFrom(
            __DIR__.'/../../config/fonnte.php',
            'fonnte'
        );

        // create image
        $app->singleton('fonnte', function ($app) {
            return new Fonnte;
        });

        $app->alias('fonnte', 'TianRosandhy\LaravelFonnte\Fonnte');

    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['fonnte'];
    }
}
