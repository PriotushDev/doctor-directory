<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditTrail;

class Prescription extends Model
{
    use HasAuditTrail;
    protected $fillable = [
        'appointment_id',
        'doctor_id',
        'patient_id',
        'diagnosis',
        'advice',
        'follow_up_date',
        'cc', 'oe', 'oh', 'mh', 'investigation',
        'age', 'sex', 'weight', 'registration_no',
    ];

    protected $casts = [
        'follow_up_date' => 'date',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function medicines()
    {
        return $this->hasMany(PrescriptionMedicine::class);
    }
}
