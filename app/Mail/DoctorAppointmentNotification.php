<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;

class DoctorAppointmentNotification extends Mailable implements ShouldQueue
{
    public $appointment;

    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    public function build()
    {
        return $this->subject('New Appointment Booked')
            ->view('emails.doctor_appointment_notification');
    }
}