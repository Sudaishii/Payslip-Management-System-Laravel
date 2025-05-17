<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin user with role_id 1
        User::create([
            'user_name' => 'Super Admin',
            'user_email' => 'superadmin@example.com',
            'password' => Hash::make('password123'),
            'role_id' => 1,
            'user_status_id' => 1, // Assuming 1 is active
        ]);

        // Create HR Admin user with role_id 2
        User::create([
            'user_name' => 'HR Admin',
            'user_email' => 'hradmin@example.com',
            'password' => Hash::make('password123'),
            'role_id' => 2,
            'user_status_id' => 1, // Assuming 1 is active
        ]);
    }
}
