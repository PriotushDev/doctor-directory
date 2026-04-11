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
        Schema::table('appointments', function (Blueprint $table) {
            $table->foreignId('chamber_id')->nullable()->after('user_id')->constrained('doctor_chambers')->nullOnDelete();
            $table->string('payment_status')->nullable()->after('notes');
            $table->string('payment_method')->nullable()->after('payment_status');
            $table->string('payment_number')->nullable()->after('payment_method');
            $table->string('transaction_id')->nullable()->after('payment_number');
            $table->decimal('amount', 10, 2)->nullable()->after('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['chamber_id']);
            $table->dropColumn([
                'chamber_id',
                'payment_status',
                'payment_method',
                'payment_number',
                'transaction_id',
                'amount',
            ]);
        });
    }
};
