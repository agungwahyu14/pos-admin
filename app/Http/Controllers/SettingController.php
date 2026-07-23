<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    public function index()
    {
        $settings = \Illuminate\Support\Facades\Cache::remember('settings.all', 3600, function () {
            return Setting::first() ?? new Setting();
        });
        return response()->json($settings);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'sometimes|string|max:255',
            'store_address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'currency' => 'sometimes|string|max:10',
            'tax_enabled' => 'sometimes|boolean',
            'tax_type' => 'sometimes|in:percentage,fixed',
            'tax_value' => 'sometimes|numeric|min:0',
            'service_enabled' => 'sometimes|boolean',
            'service_value' => 'sometimes|numeric|min:0',
        ]);

        $setting = Setting::firstOrFail();
        $setting->update($validated);

        \Illuminate\Support\Facades\Cache::forget('settings.all');

        Log::info('API: Global settings updated', [
            'ip' => $request->ip(),
            'user_id' => $request->user() ? $request->user()->id : null
        ]);

        return response()->json($setting);
    }
}
