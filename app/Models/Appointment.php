<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditTrail;

class Appointment extends Model
{
    use HasAuditTrail;
    protected $fillable = [
        'registration_id',
        'doctor_id',
        'user_id',
        'chamber_id',
        'appointment_date',
        'appointment_time',
        'status',
        'notes',
        'payment_status',
        'payment_method',
        'payment_number',
        'transaction_id',
        'amount',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($appointment) {
            $appointment->registration_id = static::generateUniqueRegistrationId();
        });
    }

    protected static function generateUniqueRegistrationId()
    {
        do {
            $id = str_pad(mt_rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
        } while (static::where('registration_id', $id)->exists());

        return $id;
    }

    protected $attributes = [
        'status' => 'pending',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chamber()
    {
        return $this->belongsTo(DoctorChamber::class, 'chamber_id');
    }

    public function prescription()
    {
        return $this->hasOne(Prescription::class);
    }
}
