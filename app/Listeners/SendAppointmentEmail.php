<?php

namespace App\Listeners;

use App\Events\AppointmentCreated;
use Illuminate\Support\Facades\Mail;

class SendAppointmentEmail
{
    public function handle(AppointmentCreated $event)
    {
        $appointment = $event->appointment;

        Mail::raw(
            "Your appointment is booked on ".$appointment->appointment_date,
            function ($message) use ($appointment) {
                $message->to($appointment->user->email)
                        ->subject('Appointment Confirmation');
            }
        );
    }
}
