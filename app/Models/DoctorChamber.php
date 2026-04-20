<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditTrail;

class DoctorChamber extends Model
{
    use HasAuditTrail;
    protected $fillable = [
        'doctor_id',
        'hospital_id',
        'day',
        'start_time',
        'end_time',
        'fee'
    ];

    /**
     * Parse time input into H:i format for database.
     */
    public function setStartTimeAttribute($value)
    {
        $this->attributes['start_time'] = $value ? date('H:i', strtotime($value)) : null;
    }

    /**
     * Parse time input into H:i format for database.
     */
    public function setEndTimeAttribute($value)
    {
        $this->attributes['end_time'] = $value ? date('H:i', strtotime($value)) : null;
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}
