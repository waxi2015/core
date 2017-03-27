<?php

namespace Waxis\Core;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if (! $this->app->routesAreCached()) {
            require __DIR__.'/routes.php';
        }

        $this->publishes([
            __DIR__.'/assets/common' => resource_path('assets/common'),
            __DIR__.'/assets/app' => resource_path('assets/app'),
            __DIR__.'/Descriptors/Image/Example.php' => app_path('Descriptors/Image/Example.php'),
            __DIR__.'/Exceptions/Handler.php' => app_path('Exceptions/Handler.php'),
            __DIR__.'/config/locale.php' => config_path('locale.php'),
            __DIR__.'/views/welcome.blade.php' => resource_path('views/welcome.blade.php'),
            __DIR__.'/gulpfile.js' => 'gulpfile.js',
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
