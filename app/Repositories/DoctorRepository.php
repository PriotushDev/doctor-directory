<?php

namespace App\Repositories;

use App\Models\Doctor;

class DoctorRepository
{
    public function getAll($request)
    {
        $query = Doctor::with(['specialty','hospital']);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('specialty_id')) {
            $query->where('specialty_id', $request->specialty_id);
        }

        if ($request->filled('hospital_id')) {
            $query->where('hospital_id', $request->hospital_id);
        }

        return $query->paginate(10);
    }

    public function create(array $data)
    {
        return Doctor::create($data);
    }

    public function findById($id)
    {
        return Doctor::with(['hospital','specialty','chambers'])->findOrFail($id);
    }

    public function update($id, array $data)
    {
        $doctor = $this->findById($id);
        $doctor->update($data);
        return $doctor;
    }

    public function delete($id)
    {
        $doctor = $this->findById($id);
        return $doctor->delete();
    }
}