<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles if not exists
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $guest = Role::firstOrCreate(['name' => 'guest']);

        // Create user
        $user = User::create([
            'employee_id' => 'EMP001',
            'name' => 'Surya',
            'division' => 'IT',
            'email' => 'admin@example.com',
            'phone_number' => '081234567890',
            'address' => 'Surabaya',
            'password' => bcrypt('password123'),
        ]);

        // Give role
        $user->assignRole($admin);
    }
}
