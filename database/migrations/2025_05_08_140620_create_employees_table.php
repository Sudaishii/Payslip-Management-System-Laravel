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
        Schema::create('employees', function (Blueprint $table) {
            $table->id('emp_id');
            $table->string('emp_fname', 50);
            $table->string('emp_middle', 255)->nullable();
            $table->string('emp_lname', 50);
            $table->integer('emp_age');
            $table->string('emp_sex', 255);
            $table->string('emp_add', 50);
            $table->string('emp_email', 50);
            $table->string('emp_contact', 50);
            $table->date('emp_hdate');
            $table->string('emp_dept', 50);
            $table->string('emp_position', 50);
            $table->unsignedBigInteger('rates_id');
            $table->foreign('rates_id')->references('rates_id')->on('rates')->onDelete('restrict');
            $table->timestamps();
        });

        // Set auto-increment start value to 1001
        DB::statement('ALTER TABLE employees AUTO_INCREMENT = 1001;');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
