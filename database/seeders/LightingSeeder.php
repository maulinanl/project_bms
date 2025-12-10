<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lighting;

class LightingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Lighting::insert([
            [
                'lighting_device_id' => 'esp32_lighting_1',
                'lighting_type' => 1,
                'lighting_brand' => 'Philips',
                'ruangan_id' => 1,
                'created_at' => now(),
            ],
            [
                'lighting_device_id' => 'esp32_lighting_2',
                'lighting_type' => 2,
                'lighting_brand' => 'APA',
                'ruangan_id' => 2,
                'created_at' => now(),
            ]
        ]);
    }
}
