<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorChamber extends Model
{
    protected $fillable = [
        'doctor_id',
        'hospital_id',
        'day',
        'start_time',
        'end_time',
        'fee'
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }
}
