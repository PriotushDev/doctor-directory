<h2>New Appointment Booked</h2>

<p>Dear Dr. {{ $appointment->doctor->name }},</p>

<p>A patient has booked an appointment.</p>

<p><strong>Patient:</strong> {{ $appointment->user->name }}</p>

<p><strong>Date:</strong> {{ $appointment->appointment_date }}</p>

<p><strong>Time:</strong> {{ $appointment->appointment_time }}</p>

<p><strong>Notes:</strong> {{ $appointment->notes }}</p>

<p>Please check your dashboard for details.</p>