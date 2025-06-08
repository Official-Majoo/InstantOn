<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

// Import the correct Intervention Image v3 classes
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register Intervention Image v3
        $this->registerInterventionImage();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fix for MySQL older than 5.7.7 or MariaDB older than 10.2.2
        Schema::defaultStringLength(191);
        
        // Register Livewire components if Livewire is installed
        $this->registerLivewireComponents();
        
        // Publish config files when running in console
        $this->publishConfigs();
    }

    /**
     * Register Intervention Image service
     * 
     * @return void
     */
    protected function registerInterventionImage(): void
    {
        // Only register if the class exists
        if (!class_exists(ImageManager::class)) {
            Log::warning('Intervention Image package not found. Image processing features will be unavailable.');
            return;
        }

        // Register the image manager singleton
        $this->app->singleton('image', function ($app) {
            try {
                // Use Imagick if available, otherwise fall back to GD
                if (extension_loaded('imagick')) {
                    Log::info('Using Imagick driver for image processing');
                    return new ImageManager(new ImagickDriver());
                }
                
                Log::info('Using GD driver for image processing');
                return new ImageManager(new GdDriver());
            } catch (\Exception $e) {
                Log::error('Failed to initialize Intervention Image: ' . $e->getMessage());
                
                // Fallback to GD driver as last resort
                return new ImageManager(new GdDriver());
            }
        });

        // Also bind ImageManager directly for dependency injection
        $this->app->bind(ImageManager::class, function ($app) {
            return $app->make('image');
        });
    }

    /**
     * Register Livewire components
     * 
     * @return void
     */
    protected function registerLivewireComponents(): void
    {
        if (class_exists('Livewire\Livewire')) {
            // Registration Components
            \Livewire\Livewire::component('registration-wizard', \App\Http\Livewire\RegistrationWizard::class);
            \Livewire\Livewire::component('facial-capture', \App\Http\Livewire\FacialCapture::class);
            \Livewire\Livewire::component('document-upload', \App\Http\Livewire\DocumentUpload::class);
            
            // Bank Officer Components
            \Livewire\Livewire::component('officer-review-queue', \App\Http\Livewire\OfficerReviewQueue::class);
        }
    }

    /**
     * Publish configuration files
     * 
     * @return void
     */
    protected function publishConfigs(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/image.php' => config_path('image.php'),
                __DIR__.'/../../config/verification.php' => config_path('verification.php'),
                __DIR__.'/../../config/facial-recognition.php' => config_path('facial-recognition.php'),
            ], 'fnbb-config');
        }
    }
}