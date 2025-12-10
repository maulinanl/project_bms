<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ruangan;

class RuanganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ruangan::insert([
            [
                'lantai_id' => 1,
                'room_name' => 'Ruang Server',
                'room_limit_beban' => 200,
                'room_lux_level' => 250,
                'room_temperature' => 21,
                'created_at' => now(),
            ],
            [
                'lantai_id' => 2,
                'room_name' => 'Ruang Meeting',
                'room_limit_beban' => 150,
                'room_lux_level' => 200,
                'room_temperature' => 22,
                'created_at' => now(),
            ]
        ]);
    }
}
