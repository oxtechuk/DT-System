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

 // Auto-detect dynamic URL Host & HTTPS / Reverse Proxy dynamically
 if (!app()->runningInConsole() && !empty($_SERVER['HTTP_HOST'])) {
 $host = $_SERVER['HTTP_HOST'];
 $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
 || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
 || (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
 || (config('app.env') === 'production' && str_starts_with((string) config('app.url', ''), 'https://'));

 if ($isHttps) {
 \Illuminate\Support\Facades\URL::forceScheme('https');
 }

 $scheme = $isHttps ? 'https' : 'http';
 \Illuminate\Support\Facades\URL::forceRootUrl($scheme . '://' . $host);
 }

 // Global Gate: Super Admin & Role-based Permissions
 \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
 if ($user instanceof \App\Models\User) {
 if ($user->role && in_array($user->role->slug, ['owner', 'admin', 'super_admin'])) {
 return true;
 }
 if ($user->id === 1 && empty($user->role_id)) {
 return true;
 }
 return $user->hasPermission($ability) ? true : null;
 }
 return null;
 });

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
