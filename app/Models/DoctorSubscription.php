<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditTrail;

class DoctorSubscription extends Model
{
    use HasAuditTrail;
    protected $fillable = [
        'doctor_id', 'user_id', 'package_id', 'promo_code_id',
        'start_date', 'end_date', 'original_price', 'discount_applied',
        'final_price', 'payment_method', 'payment_reference',
        'payment_status', 'status', 'is_trial', 'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_trial' => 'boolean',
        'original_price' => 'decimal:2',
        'discount_applied' => 'decimal:2',
        'final_price' => 'decimal:2',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(SubscriptionPackage::class, 'package_id');
    }

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }

    /**
     * Check if this subscription is currently active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active'
            && $this->payment_status === 'verified'
            && $this->end_date->gte(now()->startOfDay());
    }

    /**
     * Get remaining days before expiry.
     */
    public function getDaysRemainingAttribute(): int
    {
        return max(0, (int) now()->startOfDay()->diffInDays($this->end_date, false));
    }

    /**
     * Scope: only active subscriptions.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where('payment_status', 'verified')
                     ->where('end_date', '>=', now()->startOfDay());
    }
}
