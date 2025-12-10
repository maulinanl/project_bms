<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SinglePower;

class SinglePowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SinglePower::insert([
            [
                'single_power_device_id' => 'esp32_singlepower_1',
                'single_power_limit_beban' => 20.5,
                'single_power_brand' => 'Schneider',
                'single_power_status' => true,
                'ruangan_id' => 1,
                'created_at' => now(),
            ],
            [
                'single_power_device_id' => 'esp32_singlepower_2',
                'single_power_limit_beban' => 18.0,
                'single_power_brand' => 'CHNT',
                'single_power_status' => false,
                'ruangan_id' => 2,
                'created_at' => now(),
            ]
        ]);
    }
}
