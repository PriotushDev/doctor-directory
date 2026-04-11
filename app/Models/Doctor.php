<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'specialty_id',
        'hospital_id',
        'degree',
        'experience',
        'phone',
        'email',
        'bio',
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
}
