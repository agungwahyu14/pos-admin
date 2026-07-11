<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Cache::remember('settings.all', 3600, function () {
            return Setting::first() ?? new Setting();
        });
        
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'store_name' => 'required|string|max:255',
            'store_address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'tax_enabled' => 'nullable|boolean',
            'tax_value' => 'nullable|numeric|min:0',
            'service_enabled' => 'nullable|boolean',
            'service_value' => 'nullable|numeric|min:0',
        ]);
        
        $validated['tax_enabled'] = $request->has('tax_enabled');
        $validated['service_enabled'] = $request->has('service_enabled');
        // Ensure values are not null if enabled
        if (!$request->filled('tax_value')) {
            $validated['tax_value'] = 0;
        }
        if (!$request->filled('service_value')) {
            $validated['service_value'] = 0;
        }

        $settings = Setting::first();
        if ($settings) {
            $settings->update($validated);
        } else {
            Setting::create($validated);
        }
        
        Cache::forget('settings.all');

        return redirect()->back()->with('success', 'Settings updated successfully');
    }
}
