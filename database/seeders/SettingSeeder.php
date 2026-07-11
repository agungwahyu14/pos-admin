<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Setting::create([
            'id' => 1,
            'store_name' => '5.4.12 Coffee',
            'store_address' => '123 Coffee Ave, Seattle WA',
            'phone' => '+1 234 567 890',
            'currency' => 'IDR',
            'tax_enabled' => true,
            'tax_type' => 'percentage',
            'tax_value' => 10.00,
            'service_enabled' => true,
            'service_value' => 5.00,
        ]);
    }
}
