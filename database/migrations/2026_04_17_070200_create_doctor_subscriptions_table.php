<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('package_id')->nullable()->constrained('subscription_packages')->nullOnDelete();
            $table->foreignId('promo_code_id')->nullable()->constrained('promo_codes')->nullOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('original_price', 10, 2)->default(0);
            $table->decimal('discount_applied', 10, 2)->default(0);
            $table->decimal('final_price', 10, 2)->default(0);
            $table->enum('payment_method', ['manual', 'bkash', 'nagad', 'rocket', 'sslcommerz'])->default('manual');
            $table->string('payment_reference')->nullable();  // Transaction ID / receipt
            $table->enum('payment_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');
            $table->boolean('is_trial')->default(false);
            $table->text('notes')->nullable();                // Admin notes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_subscriptions');
    }
};
