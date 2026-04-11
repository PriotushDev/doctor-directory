<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
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
