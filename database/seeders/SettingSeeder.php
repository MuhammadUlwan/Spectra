<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
    $settings = [
        ['key_name' => 'profit_percent', 'value' => '7.5'],
        ['key_name' => 'sponsor_fee_direct', 'value' => '7.5'],
        ['key_name' => 'sponsor_fee_indirect', 'value' => '2.5'],
        ['key_name' => 'profit_sharing_level1', 'value' => '1.25'],
        ['key_name' => 'profit_sharing_level2', 'value' => '0.75'],
        ['key_name' => 'profit_sharing_level3', 'value' => '0.5'],
        ['key_name' => 'bonus_target_omset', 'value' => '10000'],
        ['key_name' => 'bonus_profit_extra', 'value' => '1.25'],
        ['key_name' => 'currency', 'value' => 'USD'],

        // 🔹 Tambahan untuk announcement slider (bisa banyak image, dipisahkan koma atau json)
        ['key_name' => 'announcement_slider', 'value' => json_encode([
            '/storage/slider/slider1.jpg',
            '/storage/slider/slider2.jpg',
            '/storage/slider/slider3.jpg',
        ])],
    ];

        foreach ($settings as $set) {
            Setting::updateOrCreate(['key_name' => $set['key_name']], $set);
        }
    }
}
