<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Gedung;

class GedungSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gedung::create([
            'gateway_id' => 1,
            'building_name' => 'Gedung A',
            'building_adress' => 'Jl. Raya ITS',
            'building_longitude' => '-7.2819',
            'building_latitude' => '112.7931',
            'building_daya' => 3200,
            'gateway_status' => true,
            'foto_building' => 'gedung_a.jpg',
        ]);
    }
}
