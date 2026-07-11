<?php

namespace App\Http\Controllers;

use App\Models\PrinterSetting;
use Illuminate\Http\Request;

class PrinterSettingController extends Controller
{
    public function index()
    {
        $setting = PrinterSetting::firstOrCreate(
            ['id' => 1],
            [
                'printer_name' => null,
                'printer_address' => null,
                'connection_type' => 'bluetooth',
                'paper_size' => 58,
                'auto_print' => true,
                'print_customer_copy' => true,
                'print_kitchen_copy' => false,
            ]
        );
        return response()->json($setting);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'printer_name' => 'nullable|string|max:255',
            'printer_address' => 'nullable|string|max:255',
            'connection_type' => 'required|string|in:bluetooth,usb,network',
            'paper_size' => 'required|integer|in:58,80',
            'auto_print' => 'required|boolean',
            'print_customer_copy' => 'required|boolean',
            'print_kitchen_copy' => 'required|boolean',
        ]);

        $setting = PrinterSetting::firstOrFail();
        $setting->update($validated);

        return response()->json($setting);
    }
}
