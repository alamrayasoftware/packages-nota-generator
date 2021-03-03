<?php

namespace ArsoftModules\NotaGenerator;

use ArsoftModules\NotaGenerator\NotaGenerator;
use Illuminate\Support\ServiceProvider;

class NotaGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('notagenerator', function ($app) {
            return new NotaGenerator();
        });
    }
}
