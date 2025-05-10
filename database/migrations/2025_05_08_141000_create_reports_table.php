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
        Schema::create('reports', function (Blueprint $table) {
            $table->increments('report_id');
            $table->unsignedInteger('emp_id');
            $table->string('month', 10);
            $table->integer('year');
            $table->decimal('total_hours', 10, 0);
            $table->decimal('total_overtime', 10, 0);
            $table->decimal('gross_salary', 10, 2);
            $table->decimal('sss', 10, 2);
            $table->decimal('phil_health', 10, 2);
            $table->decimal('pag_ibig', 10, 2);
            $table->decimal('t_deductions', 10, 2);
            $table->decimal('overtime_pay', 10, 2);
            $table->decimal('net_pay', 10, 2);
            $table->string('status', 20);
            $table->timestamp('date_generated')->useCurrent()->useCurrentOnUpdate();
            $table->timestamps();

            $table->foreign('emp_id')->references('emp_id')->on('employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
