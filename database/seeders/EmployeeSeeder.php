<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employees')->insert([
            'emp_fname' => 'John',
            'emp_middle' => 'A',
            'emp_lname' => 'Doe',
            'emp_age' => 30,
            'emp_sex' => 'Male',
            'emp_add' => '123 Main St',
            'emp_email' => 'john.doe@example.com',
            'emp_contact' => '123-456-7890',
            'emp_hdate' => '2023-01-01',
            'emp_dept' => 'IT',
            'emp_position' => 'Developer',
            'rates_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
