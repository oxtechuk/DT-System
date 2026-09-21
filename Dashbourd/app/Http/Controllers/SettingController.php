<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display general settings page
     */
    public function general()
    {
        return view('settings.general');
    }

    /**
     * Save general settings
     */
    public function saveGeneral(Request $request)
    {
        // Handle file uploads
        if ($request->hasFile('logo_main')) {
            $path = $request->file('logo_main')->store('branding', 'public');
            Setting::set('logo_main', $path, 'string', 'branding');
        }
        if ($request->hasFile('logo_sm')) {
            $path = $request->file('logo_sm')->store('branding', 'public');
            Setting::set('logo_sm', $path, 'string', 'branding');
        }
        if ($request->hasFile('favicon')) {
            $path = $request->file('favicon')->store('branding', 'public');
            Setting::set('favicon', $path, 'string', 'branding');
        }
        if ($request->hasFile('app_banner_image')) {
            $path = $request->file('app_banner_image')->store('banners', 'public');
            Setting::set('app_banner_image', $path, 'string', 'branding');
        }

        foreach ($request->except(['_token', 'logo_main', 'logo_sm', 'favicon', 'app_banner_image']) as $key => $val) {
            Setting::set($key, $val, is_bool($val) ? 'boolean' : 'string', 'general');
        }

        \Illuminate\Support\Facades\Cache::forget('site_global_settings');

        return back()->with('success', 'تم حفظ الإعدادات بنجاح');
    }
}
