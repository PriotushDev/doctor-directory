<?php

namespace App\Repositories;

use App\Models\Appointment;

class AppointmentRepository
{
    public function getAll($filters = [])
    {
        $query = Appointment::with(['doctor','user']);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate(10);
    }

    public function create(array $data)
    {
        return Appointment::create($data);
    }

    public function findById($id)
    {
        return Appointment::with(['doctor','user'])->findOrFail($id);
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