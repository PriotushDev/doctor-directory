<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditTrail;

class Hospital extends Model
{
    use HasAuditTrail;
    protected $fillable = [
        'name',
        'district_id',
        'address',
        'phone',
        'email',
        'photo',
        'url',
        'medical_test_list',
        'ambulance_number',
        'reserved_doctor_number',
        'visited_doctor_number',
        'nurse_number',
        'staff_number',
        'ICU_number',
        'CCU_number',
        'HDU_number',
        'Cabin_number'
    ];

    protected $casts = [
        'medical_test_list' => 'array',
    ];

    protected $appends = ['photo_url'];

    public function getPhotoUrlAttribute()
    {
        return $this->photo ? url('storage/' . $this->photo) : null;
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
