<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add created_by and updated_by audit columns to all major tables.
 * These track which user created or last updated each record.
 */
return new class extends Migration
{
    /**
     * Tables to add audit columns to.
     */
    private array $tables = [
        'doctors',
        'divisions',
        'districts',
        'specialties',
        'hospitals',
        'doctor_chambers',
        'appointments',
        'prescriptions',
        'medicines',
        'subscription_packages',
        'promo_codes',
        'doctor_subscriptions',
        'doctor_notifications',
        'doctor_trial_days',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $blueprint) use ($table) {
                    if (!Schema::hasColumn($table, 'created_by')) {
                        $blueprint->unsignedBigInteger('created_by')->nullable()->after('updated_at');
                        $blueprint->foreign('created_by')->references('id')->on('users')->nullOnDelete();
                    }
                    if (!Schema::hasColumn($table, 'updated_by')) {
                        $blueprint->unsignedBigInteger('updated_by')->nullable()->after('created_by');
                        $blueprint->foreign('updated_by')->references('id')->on('users')->nullOnDelete();
                    }
                });
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $blueprint) use ($table) {
                    // Drop foreign keys first
                    if (Schema::hasColumn($table, 'created_by')) {
                        $blueprint->dropForeign([$table . '_created_by_foreign']);
                        $blueprint->dropColumn('created_by');
                    }
                    if (Schema::hasColumn($table, 'updated_by')) {
                        $blueprint->dropForeign([$table . '_updated_by_foreign']);
                        $blueprint->dropColumn('updated_by');
                    }
                });
            }
        }
    }
};
