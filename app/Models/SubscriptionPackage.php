<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditTrail;

class SubscriptionPackage extends Model
{
    use HasAuditTrail;
    protected $fillable = [
        'name', 'description', 'duration_months', 'price',
        'discount_percent', 'discount_amount', 'features',
        'is_popular', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'features' => 'array',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    public function subscriptions()
    {
        return $this->hasMany(DoctorSubscription::class, 'package_id');
    }

    /**
     * Get the effective price after package-level discounts.
     */
    public function getEffectivePriceAttribute()
    {
        $price = $this->price;

        if ($this->discount_percent > 0) {
            $price -= ($price * $this->discount_percent / 100);
        }
        if ($this->discount_amount > 0) {
            $price -= $this->discount_amount;
        }

        return max(0, round($price, 2));
    }
}
