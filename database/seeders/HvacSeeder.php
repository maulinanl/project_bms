<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Hvac;

class HvacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Hvac::insert([
            [
                'hvac_type' => 1,
                'hvac_device_id' => 'esp32_ac_1',
                'hvac_brand' => 'Daikin',
                'hvac_pk' => 2.0,
                'hvac_status' => true,
                'ruangan_id' => 1,
                'created_at' => now(),
            ],
            [
                'hvac_type' => 2,
                'hvac_device_id' => 'esp32_ac_2',
                'hvac_brand' => 'Samsung',
                'hvac_pk' => 1.5,
                'hvac_status' => false,
                'ruangan_id' => 2,
                'created_at' => now(),
            ]
        ]);
    }
}
