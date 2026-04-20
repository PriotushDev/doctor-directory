<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

/**
 * HasAuditTrail — Automatically sets created_by and updated_by
 * on model create and update events.
 *
 * Usage: Add `use HasAuditTrail;` inside any Eloquent model.
 */
trait HasAuditTrail
{
    /**
     * Boot the trait: register model events to auto-fill audit fields.
     */
    public static function bootHasAuditTrail(): void
    {
        static::creating(function ($model) {
            if (Auth::check() && $model->isFillable('created_by') || in_array('created_by', $model->getAuditColumns())) {
                $model->created_by = $model->created_by ?? Auth::id();
            }
            if (Auth::check() && $model->isFillable('updated_by') || in_array('updated_by', $model->getAuditColumns())) {
                $model->updated_by = $model->updated_by ?? Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
    }

    /**
     * Get the audit column names (override in model if needed).
     */
    public function getAuditColumns(): array
    {
        return ['created_by', 'updated_by'];
    }

    /**
     * Initialize the trait — merge audit columns into fillable.
     */
    public function initializeHasAuditTrail(): void
    {
        $this->mergeFillable(['created_by', 'updated_by']);
    }

    // ===== RELATIONSHIPS =====

    /**
     * The user who created this record.
     */
    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    /**
     * The user who last updated this record.
     */
    public function updater()
    {
        return $this->belongsTo(\App\Models\User::class, 'updated_by');
    }
}
