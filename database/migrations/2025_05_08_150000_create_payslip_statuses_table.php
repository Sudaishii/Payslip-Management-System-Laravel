`<?php

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
        Schema::create('payslip_statuses', function (Blueprint $table) {
            $table->id('payslip_status_id');
            $table->string('name', 50)->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Insert default statuses
        DB::table('payslip_statuses')->insert([
            ['name' => 'generated', 'description' => 'Report generated but not released', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'released', 'description' => 'Report released to employee', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'on hold', 'description' => 'Report on hold', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'cancelled', 'description' => 'Report cancelled', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payslip_statuses');
    }
};
