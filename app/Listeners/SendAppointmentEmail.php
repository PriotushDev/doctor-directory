<?php

namespace App\Listeners;

use App\Events\AppointmentCreated;
use App\Mail\AppointmentBooked;
use Illuminate\Support\Facades\Mail;
use App\Mail\DoctorAppointmentNotification;

class SendAppointmentEmail
{
    public function handle(AppointmentCreated $event)
    {
        $appointment = $event->appointment;

        $user = $appointment->user;
        $doctor = $appointment->doctor;

        // Mail to patient
        Mail::to($user->email)
            ->send(new AppointmentBooked($appointment));
        
        // Mail to doctor
        Mail::to($doctor->email)
            ->send(new DoctorAppointmentNotification($appointment));
    }
}
