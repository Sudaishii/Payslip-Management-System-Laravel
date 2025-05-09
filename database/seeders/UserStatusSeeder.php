<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class UserStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $statuses = [
            ['name' => 'Active', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Inactive', 'created_at' => $now, 'updated_at' => $now],
        ];

        foreach ($statuses as $status) {
            DB::table('user_status')->updateOrInsert(
                ['name' => $status['name']],
                $status
            );
        }
    }
}
