<?php

namespace App\Listeners;

use App\Events\AppointmentCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAppointmentEmail implements ShouldQueue
{
    public function handle(AppointmentCreated $event)
    {
        $appointment = $event->appointment;

        $user = $appointment->user;

        Mail::raw(
            "Your appointment is booked on ".$appointment->appointment_date,
            function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Appointment Confirmation');
            }
        );
    }
}
