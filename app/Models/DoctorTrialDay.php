<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditTrail;

class DoctorTrialDay extends Model
{
    use HasAuditTrail;
    protected $fillable = [
        'doctor_id', 'user_id', 'trial_days',
        'start_date', 'end_date', 'granted_by', 'reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function grantedBy()
    {
        return $this->belongsTo(User::class, 'granted_by');
    }

    /**
     * Check if this trial is currently active.
     */
    public function isActive(): bool
    {
        return $this->end_date->gte(now()->startOfDay());
    }

    /**
     * Scope: only active trials.
     */
    public function scopeActive($query)
    {
        return $query->where('end_date', '>=', now()->startOfDay());
    }
}
