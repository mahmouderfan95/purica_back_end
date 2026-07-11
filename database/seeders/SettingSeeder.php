<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Setting::query()->count() === 0) {

            Setting::query()->create([
                'site_name'        => 'Minister Fitness',
                'site_description' =>
                    'Best fitness and health products in Egypt',
                'site_logo'        => null,
                'site_address'     => 'Cairo, Egypt',
                'site_phone'       => '01000000000',
                'whatsapp'         => '01000000000',
                'facebook'         => 'https://facebook.com',
                'tiktok'           => 'https://tiktok.com',
                'instagram'        => 'https://instagram.com'
            ]);
        }
    }
}
