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
        Schema::table('doctors', function (Blueprint $table) {
            if (!Schema::hasColumn('doctors', 'name_bn')) {
                $table->string('name_bn')->nullable()->after('name');
            }
            if (!Schema::hasColumn('doctors', 'specialty_bn')) {
                $table->string('specialty_bn')->nullable()->after('specialty_id');
            }
            if (!Schema::hasColumn('doctors', 'degree_bn')) {
                $table->string('degree_bn')->nullable()->after('degree');
            }
            if (!Schema::hasColumn('doctors', 'degree1_bn')) {
                $table->string('degree1_bn')->nullable()->after('degree1');
            }
            if (!Schema::hasColumn('doctors', 'degree2_bn')) {
                $table->string('degree2_bn')->nullable()->after('degree2');
            }
            if (!Schema::hasColumn('doctors', 'degree3_bn')) {
                $table->string('degree3_bn')->nullable()->after('degree3');
            }
            if (!Schema::hasColumn('doctors', 'degree4_bn')) {
                $table->string('degree4_bn')->nullable()->after('degree4');
            }
            if (!Schema::hasColumn('doctors', 'workplace_bn')) {
                $table->string('workplace_bn')->nullable()->after('workplace');
            }
            if (!Schema::hasColumn('doctors', 'slug_bn')) {
                $table->string('slug_bn')->nullable()->after('slug');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn([
                'name_bn',
                'specialty_bn',
                'degree_bn',
                'degree1_bn',
                'degree2_bn',
                'degree3_bn',
                'degree4_bn',
                'workplace_bn',
                'slug_bn'
            ]);
        });
    }
};
