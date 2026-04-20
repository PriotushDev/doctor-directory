<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // e.g. "Starter", "Professional"
            $table->text('description')->nullable();
            $table->integer('duration_months');               // 1, 3, 6, 12
            $table->decimal('price', 10, 2);                 // Base price in BDT
            $table->decimal('discount_percent', 5, 2)->default(0); // e.g. 10.00 = 10%
            $table->decimal('discount_amount', 10, 2)->default(0); // Flat discount
            $table->json('features')->nullable();            // ["appointments", "prescriptions", ...]
            $table->boolean('is_popular')->default(false);   // Highlight badge
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_packages');
    }
};
