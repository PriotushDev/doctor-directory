<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditTrail;

class PromoCode extends Model
{
    use HasAuditTrail;
    protected $fillable = [
        'code', 'discount_type', 'discount_value',
        'max_uses', 'used_count', 'valid_from', 'valid_until', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'discount_value' => 'decimal:2',
    ];

    /**
     * Check if this promo code is currently valid and usable.
     */
    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        if (now()->lt($this->valid_from) || now()->gt($this->valid_until)) return false;
        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) return false;
        return true;
    }

    /**
     * Calculate discount amount for a given price.
     */
    public function calculateDiscount(float $price): float
    {
        if ($this->discount_type === 'percent') {
            return round($price * $this->discount_value / 100, 2);
        }
        return min($this->discount_value, $price); // Fixed discount can't exceed price
    }
}
