<?php

namespace App\Services;

use App\Repositories\AppointmentRepository;
use App\Models\Appointment;
use App\Events\AppointmentCreated;

class AppointmentService
{
    protected $appointmentRepository;

    public function __construct(AppointmentRepository $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    public function listAppointments()
    {
        return $this->appointmentRepository->getAll();
    }

    public function createAppointment(array $data)
    {
        // 🚨 Prevent double booking
        $exists = Appointment::where('doctor_id', $data['doctor_id'])
            ->where('appointment_date', $data['appointment_date'])
            ->where('appointment_time', $data['appointment_time'])
            ->exists();

        if ($exists) {
            throw new \Exception('This time slot is already booked');
        }

        $data['user_id'] = auth()->id();
        $data['status'] = 'pending';

        $appointment = $this->appointmentRepository->create($data);

        event(new AppointmentCreated($appointment));

        return $appointment;
    }

    public function getAppointmentById($id)
    {
        return $this->appointmentRepository->findById($id);
    }

    public function updateAppointment($id, array $data)
    {
        $appointment = $this->appointmentRepository->findById($id);

        // 🔥 Prevent double booking when updating
        if (isset($data['doctor_id']) || isset($data['appointment_date']) || isset($data['appointment_time'])) {

            $doctorId = $data['doctor_id'] ?? $appointment->doctor_id;
            $date = $data['appointment_date'] ?? $appointment->appointment_date;
            $time = $data['appointment_time'] ?? $appointment->appointment_time;

            $exists = \App\Models\Appointment::where('doctor_id', $doctorId)
                ->where('appointment_date', $date)
                ->where('appointment_time', $time)
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                throw new \Exception('This time slot is already booked');
            }
        }

        return $this->appointmentRepository->update($id, $data);
    }



    public function deleteAppointment($id)
    {
        return $this->appointmentRepository->delete($id);
    }
}