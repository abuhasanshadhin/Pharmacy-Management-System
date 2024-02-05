<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['name' => 'app_name',          'value' => 'PharmacyCo'],
            ['name' => 'title',             'value' => 'Innovative Solutions for Modern Pharmacies'],
            ['name' => 'currency',          'value' => 'USD'],
            ['name' => 'currency_symbol',   'value' => '$'],
            ['name' => 'phone',             'value' => '+1 (506) 803-6171'],
            ['name' => 'email',             'value' => 'tadag@mailinator.com'],
            ['name' => 'address',           'value' => '56 Elm Avenue, Unit B, Townsville, State'],
            ['name' => 'footer_text',       'value' => '© 2024 Apex Pharmacy. All rights reserved.'],
            ['name' => 'primary_color',     'value' => '#002afa'],
            ['name' => 'logo_visible',      'value' => 'application_name'],
            ['name' => 'favicon',           'value' => 'setting/favicon.png'],
            ['name' => 'logo',              'value' => 'setting/logo.png'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['name' => $setting['name'], 'value' => $setting['value']],
                $setting
            );
        }
    }
}
