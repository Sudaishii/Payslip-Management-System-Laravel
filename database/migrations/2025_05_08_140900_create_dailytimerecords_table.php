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
        Schema::create('dailytimerecords', function (Blueprint $table) {
            $table->increments('record_id');
            $table->unsignedInteger('employee_id');
            $table->date('entry_date');
            $table->time('time_in');
            $table->time('time_out');
            $table->string('month', 20);
            $table->decimal('hours_worked', 10, 0);
            $table->decimal('overtime_hrs', 10, 0);
            $table->string('absent', 5);
            $table->timestamps();

            $table->foreign('employee_id')->references('emp_id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dailytimerecords');
    }
};
