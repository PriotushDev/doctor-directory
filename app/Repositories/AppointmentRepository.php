<?php

namespace App\Repositories;

use App\Models\Appointment;

class AppointmentRepository
{
    public function getAll()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return Appointment::with(['doctor','user'])->latest()->paginate(10);
        }

        return Appointment::with(['doctor'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);
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