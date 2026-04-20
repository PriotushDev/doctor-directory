<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasAuditTrail;

class DoctorNotification extends Model
{
    use HasAuditTrail;
    protected $fillable = [
        'doctor_id', 'user_id', 'title', 'message', 'type',
        'is_read', 'is_popup', 'sent_by',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_popup' => 'boolean',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function reads()
    {
        return $this->hasMany(NotificationRead::class, 'notification_id');
    }

    /**
     * Scope: unread notifications for a specific doctor.
     */
    public function scopeUnreadFor($query, $doctorId)
    {
        return $query->where(function ($q) use ($doctorId) {
            // Direct messages that are unread
            $q->where('doctor_id', $doctorId)
              ->where('is_read', false);
        })->orWhere(function ($q) use ($doctorId) {
            // Broadcasts that the doctor hasn't read yet
            $q->whereNull('doctor_id')
              ->whereDoesntHave('reads', function ($qr) use ($doctorId) {
                  $qr->where('doctor_id', $doctorId);
              });
        });
    }

    /**
     * Scope: unread popup notifications.
     */
    public function scopePopupFor($query, $doctorId)
    {
        return $query->where('is_popup', true)
                     ->unreadFor($doctorId);
    }
}
