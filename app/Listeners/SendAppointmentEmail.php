<?php

namespace App\Listeners;

use App\Events\AppointmentCreated;
use App\Mail\AppointmentBooked;
use Illuminate\Support\Facades\Mail;

class SendAppointmentEmail
{
    public function handle(AppointmentCreated $event)
    {
        $appointment = $event->appointment;

        $user = $appointment->user;

        Mail::to($user->email)->send(new AppointmentBooked($appointment));
    }
}
