<?php

namespace App\Repositories; 

use App\Models\Appointment; 

class AppointmentRepository
{
    public function create(array $data)
    {
        return Appointment::create($data);
    }

    public function getAll()
    {
        return Appointment::with(['doctor', 'user'])->latest()->get();
    }

    public function findById($id)
    {
        return Appointment::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $appointment = $this->findById($id);

        $appointment->update($data);

        return $appointment;
    }

    public function delete($id)
    {
        $appointment = $this->findById($id);

        return $appointment->delete();
    }
}