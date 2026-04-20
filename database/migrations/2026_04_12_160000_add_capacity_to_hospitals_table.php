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
        Schema::table('hospitals', function (Blueprint $table) {
            $table->json('medical_test_list')->nullable();
            $table->string('ambulance_number')->nullable();
            $table->integer('reserved_doctor_number')->nullable();
            $table->integer('visited_doctor_number')->nullable();
            $table->integer('nurse_number')->nullable();
            $table->integer('staff_number')->nullable();
            $table->integer('ICU_number')->nullable();
            $table->integer('CCU_number')->nullable();
            $table->integer('HDU_number')->nullable();
            $table->integer('Cabin_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hospitals', function (Blueprint $table) {
            $table->dropColumn([
                'medical_test_list',
                'ambulance_number',
                'reserved_doctor_number',
                'visited_doctor_number',
                'nurse_number',
                'staff_number',
                'ICU_number',
                'CCU_number',
                'HDU_number',
                'Cabin_number'
            ]);
        });
    }
};
