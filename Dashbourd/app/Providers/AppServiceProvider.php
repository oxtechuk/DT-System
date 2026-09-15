<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                $logo = \App\Models\Setting::get('logo_main');
                view()->share('settingsLogo', $logo);

                $primaryColor = \App\Models\Setting::get('primary_color');
                view()->share('appPrimaryColor', $primaryColor);
            }
        } catch (\Throwable $e) {
            // Silently continue if database not ready yet during build/migrations
        }
    }
}
