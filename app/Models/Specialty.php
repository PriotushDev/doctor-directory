<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditTrail;

class Specialty extends Model
{
    use HasAuditTrail;
    protected $fillable = [
        'name',
        'slug'
    ];

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
