<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubscriptionPackage;

class SubscriptionPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Starter',
                'description' => 'Perfect for getting started. Try all features for 1 month.',
                'duration_months' => 1,
                'price' => 500.00,
                'discount_percent' => 0,
                'discount_amount' => 0,
                'features' => ['appointments', 'prescriptions', 'medicines', 'notes', 'payments'],
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Professional',
                'description' => 'Our most popular plan. Save 15% with quarterly billing.',
                'duration_months' => 3,
                'price' => 1500.00,
                'discount_percent' => 15,
                'discount_amount' => 0,
                'features' => ['appointments', 'prescriptions', 'medicines', 'notes', 'payments', 'priority_support'],
                'is_popular' => true,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Premium',
                'description' => 'Best value. Save 25% with semi-annual billing.',
                'duration_months' => 6,
                'price' => 3000.00,
                'discount_percent' => 25,
                'discount_amount' => 0,
                'features' => ['appointments', 'prescriptions', 'medicines', 'notes', 'payments', 'priority_support', 'analytics'],
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Enterprise',
                'description' => 'Maximum savings. Save 35% with annual billing.',
                'duration_months' => 12,
                'price' => 6000.00,
                'discount_percent' => 35,
                'discount_amount' => 0,
                'features' => ['appointments', 'prescriptions', 'medicines', 'notes', 'payments', 'priority_support', 'analytics', 'custom_branding'],
                'is_popular' => false,
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($packages as $pkg) {
            SubscriptionPackage::firstOrCreate(
                ['name' => $pkg['name'], 'duration_months' => $pkg['duration_months']],
                $pkg
            );
        }
    }
}
