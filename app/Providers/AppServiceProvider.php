<?php

namespace App\Providers;

use App\Models\Customer;
use App\Models\Deal;
use App\Models\Product;
use App\Models\Setting;
use App\Models\TeacherVisit;
use App\Models\User;
use App\Observers\CustomerHubSpotObserver;
use App\Observers\DealHubSpotObserver;
use App\Observers\ProductHubSpotObserver;
use App\Observers\TeacherVisitObserver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
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
        Schema::defaultStringLength(191);

        // Auto-detect dynamic URL Host & HTTPS / Reverse Proxy dynamically
        if (! app()->runningInConsole() && ! empty($_SERVER['HTTP_HOST'])) {
            $host = $_SERVER['HTTP_HOST'];
            $isHttps = (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (! empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
            || (! empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
            || (config('app.env') === 'production' && str_starts_with((string) config('app.url', ''), 'https://'));

            if ($isHttps) {
                URL::forceScheme('https');
            }

            $scheme = $isHttps ? 'https' : 'http';
            URL::forceRootUrl($scheme.'://'.$host);
        }

        // Global Gate: Super Admin & Role-based Permissions
        Gate::before(function ($user, $ability) {
            if ($user instanceof User) {
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
            $settings = Cache::remember('site_global_settings', 1800, function () {
                return Setting::getAllAsArray();
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
            Customer::observe(CustomerHubSpotObserver::class);
            Deal::observe(DealHubSpotObserver::class);
            Product::observe(ProductHubSpotObserver::class);
            // Teacher commission auto-calculation
            TeacherVisit::observe(TeacherVisitObserver::class);
        } catch (\Throwable $e) {
            // Silently continue if database not ready yet during build/migrations
        }
    }
}
