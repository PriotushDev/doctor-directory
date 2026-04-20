<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditTrail;

class Medicine extends Model
{
    use HasFactory, HasAuditTrail;

    protected $fillable = [
        'medicine_name',
        'generic_name',
        'strength',
        'dosage_type',
        'company_name',
    ];
}
