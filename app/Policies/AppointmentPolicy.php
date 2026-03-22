<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Appointment;

class AppointmentPolicy
{
    /**
     * User নিজের appointment দেখতে পারবে
     */
    public function view(User $user, Appointment $appointment)
    {
        // Admin → সব দেখতে পারবে
        if ($user->hasRole('admin')) {
            return true;
        }

        // User → শুধু নিজের
        return $user->id === $appointment->user_id;
    }

    /**
     * User নিজের appointment delete করতে পারবে
     */
    public function delete(User $user, Appointment $appointment)
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->id === $appointment->user_id;
    }
}