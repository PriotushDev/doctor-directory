<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Appointment;
use App\Models\Doctor;

class AppointmentPolicy
{
    /**
     * Admin can view all. Doctor can view their own patients. User can view own.
     */
    public function view(User $user, Appointment $appointment)
    {
        if ($user->hasRole('admin') || $user->hasRole('manager')) {
            return true;
        }

        // Doctor: can view appointments for their linked doctor profile
        if ($user->hasRole('doctor')) {
            $doctor = Doctor::where('user_id', $user->id)
                ->orWhere('email', $user->email)
                ->first();
            return $doctor && $appointment->doctor_id === $doctor->id;
        }

        return $user->id === $appointment->user_id;
    }

    /**
     * Admin can update all. Doctor can update (change status) their own patient appointments.
     * Regular user can update only their own appointment.
     */
    public function update(User $user, Appointment $appointment)
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        // Doctor: can change status of their own patients' appointments
        if ($user->hasRole('doctor')) {
            $doctor = Doctor::where('user_id', $user->id)
                ->orWhere('email', $user->email)
                ->first();
            return $doctor && $appointment->doctor_id === $doctor->id;
        }

        return $user->id === $appointment->user_id;
    }

    public function delete(User $user, Appointment $appointment)
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->id === $appointment->user_id;
    }
}