<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditTrail;

class District extends Model
{
    use HasAuditTrail;
    protected $fillable = [
        'name',
        'division_id'
    ];

    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    public function hospitals()
    {
        return $this->hasMany(Hospital::class);
    }
}
