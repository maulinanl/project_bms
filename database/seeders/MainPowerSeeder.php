<?php

namespace Database\Seeders;
use App\Models\MainPower;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MainPowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MainPower::create([
            'main_power_device_id' => 'esp32_mainpower_1',
            'main_power_status' => true,
            'gedung_id' => 1,
        ]);
    }
}
