<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditTrail;

class Doctor extends Model
{
    use HasAuditTrail;
    protected $fillable = [
        'name',
        'name_bn',
        'slug',
        'slug_bn',
        'user_id',
        'specialty_id',
        'specialty_bn',
        'hospital_id',
        'degree',
        'degree_bn',
        'degree1',
        'degree1_bn',
        'degree2',
        'degree2_bn',
        'degree3',
        'degree3_bn',
        'degree4',
        'degree4_bn',
        'workplace',
        'workplace_bn',
        'bmdc',
        'fee',
        'experience',
        'phone',
        'email',
        'bio',
        'long_bio',
        'photo'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    public function chambers()
    {
        return $this->hasMany(DoctorChamber::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(DoctorSubscription::class);
    }

    public function activeSubscription()
    {
        return $this->hasOne(DoctorSubscription::class)
                    ->where('status', 'active')
                    ->where('payment_status', 'verified')
                    ->where('end_date', '>=', now()->startOfDay())
                    ->latest('end_date');
    }

    public function trialDays()
    {
        return $this->hasMany(DoctorTrialDay::class);
    }

    public function activeTrialDays()
    {
        return $this->hasMany(DoctorTrialDay::class)
                    ->where('end_date', '>=', now()->startOfDay());
    }

    public function notifications()
    {
        return $this->hasMany(DoctorNotification::class);
    }

    /**
     * Check if doctor has active access (subscription OR trial).
     */
    public function hasActiveAccess(): bool
    {
        // Check active subscription
        $hasSub = $this->subscriptions()
            ->where('status', 'active')
            ->where('payment_status', 'verified')
            ->where('end_date', '>=', now()->startOfDay())
            ->exists();

        if ($hasSub) return true;

        // Check active trial
        return $this->trialDays()
            ->where('end_date', '>=', now()->startOfDay())
            ->exists();
    }
}
