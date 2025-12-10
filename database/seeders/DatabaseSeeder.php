<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            GedungSeeder::class,
            LantaiSeeder::class,
            RuanganSeeder::class,
            MainPowerSeeder::class,
            SinglePowerSeeder::class,
            HvacSeeder::class,
            LightingSeeder::class,
            UserSeeder::class,
        ]);
    }
}
