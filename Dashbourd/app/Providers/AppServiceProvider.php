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
 \Illuminate\Support\Facades\Schema::defaultStringLength(191);

 try {
 $settings = \Illuminate\Support\Facades\Cache::remember('site_global_settings', 1800, function () {
 return \App\Models\Setting::getAllAsArray();
 });

 view()->share('allSettings', $settings);

 $logo = $settings['logo_main'] ?? null;
 view()->share('settingsLogo', $logo);

 $logoSm = $settings['logo_sm'] ?? null;
 view()->share('settingsLogoSm', $logoSm);

 $favicon = $settings['favicon'] ?? null;
 view()->share('settingsFavicon', $favicon);

 $workspaceName = $settings['workspace_name'] ?? 'DT-System Workspace';
 view()->share('workspaceName', $workspaceName);

 $workspaceSlogan = $settings['workspace_slogan'] ?? 'مساحة العمل المتكاملة';
 view()->share('workspaceSlogan', $workspaceSlogan);

 $primaryColor = $settings['primary_color'] ?? '#6366f1';
 view()->share('appPrimaryColor', $primaryColor);

 // Register HubSpot auto-sync observers
 \App\Models\Customer::observe(\App\Observers\CustomerHubSpotObserver::class);
 \App\Models\Deal::observe(\App\Observers\DealHubSpotObserver::class);
 \App\Models\Product::observe(\App\Observers\ProductHubSpotObserver::class);
 } catch (\Throwable $e) {
 // Silently continue if database not ready yet during build/migrations
 }
 }
}
