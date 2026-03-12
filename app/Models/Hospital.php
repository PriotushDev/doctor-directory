<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    protected $fillable = [
        'name',
        'district_id',
        'address',
        'phone'
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
