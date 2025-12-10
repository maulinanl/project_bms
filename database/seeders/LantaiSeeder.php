<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Lantai;

class LantaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Lantai::insert([
            [
                'gedung_id' => 1,
                'floor_number' => 1,
                'floor_limit_beban' => 1000,
                'floor_temperature' => 25.0,
                'floor_lux_level' => 300,
                'created_at' => now(),
            ],
            [
                'gedung_id' => 1,
                'floor_number' => 2,
                'floor_limit_beban' => 1000,
                'floor_temperature' => 24.5,
                'floor_lux_level' => 320,
                'created_at' => now(),
            ]
        ]);
    }
}
