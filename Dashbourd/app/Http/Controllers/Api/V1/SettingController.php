<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * GET /api/v1/settings
     */
    public function index(): JsonResponse
    {
        $settings = Setting::getAllAsArray();
        return response()->json([
            'success' => true,
            'data'    => $settings,
        ]);
    }

    /**
     * POST /api/v1/settings
     * Updates key-value settings and handles logo file uploads.
     */
    public function update(Request $request): JsonResponse
    {
        // Handle logo main upload
        if ($request->hasFile('logo_main')) {
            $file = $request->file('logo_main');
            $path = $file->store('branding', 'public');
            Setting::set('logo_main', $path, 'string', 'branding');
        }

        // Handle logo small upload
        if ($request->hasFile('logo_sm')) {
            $file = $request->file('logo_sm');
            $path = $file->store('branding', 'public');
            Setting::set('logo_sm', $path, 'string', 'branding');
        }

        // Text & color settings
        $fields = [
            'primary_color'   => 'branding',
            'workspace_name'  => 'general',
            'workspace_phone' => 'general',
            'workspace_email' => 'general',
            'workspace_address' => 'general',
            'currency'        => 'finance',
            'tax_percentage'  => 'finance',
        ];

        foreach ($fields as $field => $group) {
            if ($request->filled($field)) {
                Setting::set($field, $request->input($field), 'string', $group);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully / تم حفظ الإعدادات بنجاح',
            'data'    => Setting::getAllAsArray(),
        ]);
    }
}
