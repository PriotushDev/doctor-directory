<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->renameColumn('name', 'medicine_name');
            $table->string('strength')->nullable();
            $table->string('dosage_type')->nullable();
            $table->string('company_name')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->renameColumn('medicine_name', 'name');
            $table->dropColumn(['strength', 'dosage_type', 'company_name']);
        });
    }
};
