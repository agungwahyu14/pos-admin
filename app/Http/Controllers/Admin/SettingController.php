<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Carbon\Carbon;
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
            'store_name'      => 'required|string|max:255',
            'store_address'   => 'nullable|string',
            'phone'           => 'nullable|string|max:20',
            'tax_enabled'     => 'nullable|boolean',
            'tax_value'       => 'nullable|numeric|min:0',
            'service_enabled' => 'nullable|boolean',
            'service_value'   => 'nullable|numeric|min:0',
            'tanggal_diubah'  => ['nullable', 'string', 'regex:/^\d{2}-\d{2}-\d{4}$/'],
        ]);

        $validated['tax_enabled']     = $request->has('tax_enabled');
        $validated['service_enabled'] = $request->has('service_enabled');

        if (!$request->filled('tax_value')) {
            $validated['tax_value'] = 0;
        }
        if (!$request->filled('service_value')) {
            $validated['service_value'] = 0;
        }

        // Parse format DD-MM-YYYY ke Carbon, lalu pisahkan dari data kolom biasa
        $tanggalDiubah = null;
        if ($request->filled('tanggal_diubah')) {
            $tanggalDiubah = Carbon::createFromFormat('d-m-Y', $request->tanggal_diubah)->startOfDay();
        }
        unset($validated['tanggal_diubah']);

        $settings = Setting::first();
        if ($settings) {
            $settings->update($validated);

            // Simpan tanggal_diubah ke updated_at secara manual jika diisi
            if ($tanggalDiubah) {
                $settings->timestamps = false;
                $settings->updated_at = $tanggalDiubah;
                $settings->save();
            }
        } else {
            $settings = Setting::create($validated);

            if ($tanggalDiubah) {
                $settings->timestamps = false;
                $settings->updated_at = $tanggalDiubah;
                $settings->save();
            }
        }

        Cache::forget('settings.all');

        return redirect()->back()->with('success', 'Settings updated successfully');
    }
}
