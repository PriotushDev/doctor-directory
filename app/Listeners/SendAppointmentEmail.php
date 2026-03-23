<?php

namespace App\Listeners;

use App\Events\AppointmentCreated;
use App\Mail\AppointmentBooked;
use App\Mail\DoctorAppointmentNotification;
use Illuminate\Support\Facades\Mail;

class SendAppointmentEmail
{
    public function handle(AppointmentCreated $event)
    {
        $appointment = $event->appointment;

        Mail::to($appointment->user->email)
            ->queue(new AppointmentBooked($appointment));

        Mail::to($appointment->doctor->email)
            ->queue(new DoctorAppointmentNotification($appointment));
    }
}