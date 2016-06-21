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
            __DIR__.'/assets/common/libs/jquery' => resource_path('assets/common/libs/jquery'),
            __DIR__.'/assets/common/libs/jquery-ui' => resource_path('assets/common/libs/jquery-ui'),
            __DIR__.'/assets/app/sass/app.scss' => resource_path('assets/app/sass/app.scss'),
            __DIR__.'/assets/app/sass/bootstrap.scss' => resource_path('assets/app/sass/bootstrap.scss'),
            __DIR__.'/Descriptors/Image/Example.php' => app_path('Descriptors/Image/Example.php'),
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
