<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->id('rates_id');
            $table->string('position', 255);
            $table->decimal('hourly_rate', 10, 2);
            $table->timestamps();
        });

        // Insert predefined rates data
        DB::table('rates')->insert([
            ['position' => 'General Manager', 'hourly_rate' => 120.00],
            ['position' => 'HR Manager', 'hourly_rate' => 150.00],
            ['position' => 'Finance Manager', 'hourly_rate' => 110.00],
            ['position' => 'Finance Clerk', 'hourly_rate' => 80.00],
            ['position' => 'Front Office Manager', 'hourly_rate' => 100.00],
            ['position' => 'Receptionist', 'hourly_rate' => 95.00],
            ['position' => 'Porter', 'hourly_rate' => 50.00],
            ['position' => 'Reservation Clerk', 'hourly_rate' => 45.00],
            ['position' => 'Executive Housekeeper', 'hourly_rate' => 130.00],
            ['position' => 'Housekeeping Supervisor', 'hourly_rate' => 80.00],
            ['position' => 'Room Attendant', 'hourly_rate' => 50.00],
            ['position' => 'Public Area Cleaner', 'hourly_rate' => 40.00],
            ['position' => 'Chief Engineer', 'hourly_rate' => 180.00],
            ['position' => 'Maintenance Supervisor', 'hourly_rate' => 160.00],
            ['position' => 'Maintenance Technician', 'hourly_rate' => 170.00],
            ['position' => 'Groundskeeper', 'hourly_rate' => 50.00],
            ['position' => 'IT Manager', 'hourly_rate' => 200.00],
            ['position' => 'IT Support Specialist', 'hourly_rate' => 120.00],
            ['position' => 'Network Administrator', 'hourly_rate' => 110.00],
            ['position' => 'System Administrator', 'hourly_rate' => 130.00],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rates');
    }
};
