<?php

namespace App\Services;

use App\Repositories\AppointmentRepository;

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
    
    public function createAppointment($request)
    {
        $data = [
            'doctor_id' => $request->doctor_id,
            'user_id' => auth()->id(),
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'notes' => $request->notes,
            'status' => 'pending'
        ];

        return $this->appointmentRepository->create($data);
    }


    public function getAppointmentById($id)
    {
        return $this->appointmentRepository->findById($id);
    }

    public function updateAppointment($id, $request)
    {
        $data = [
            'doctor_id' => $request->doctor_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'notes' => $request->notes
        ];

        return $this->appointmentRepository->update($id, $data);
    }

    public function deleteAppointment($id)
    {
        return $this->appointmentRepository->delete($id);
    }
}