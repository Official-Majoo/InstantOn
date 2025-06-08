<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;

class ImageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('image', function ($app) {
            $driver = config('image.driver', 'gd');
            
            return new ImageManager(
                $driver === 'imagick' && extension_loaded('imagick') 
                    ? new ImagickDriver() 
                    : new GdDriver()
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/image.php' => config_path('image.php'),
        ]);
    }
}