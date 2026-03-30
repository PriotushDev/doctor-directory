<?php

namespace App\Repositories;

use App\Models\Doctor;

class DoctorRepository
{
    public function getAll($request)
    {
        $query = Doctor::with(['specialty','hospital']);

        if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {

                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%')

                ->orWhereHas('specialty', function ($q2) use ($request) {
                    $q2->where('name', 'like', '%' . $request->search . '%');
                })

                ->orWhereHas('hospital', function ($q2) use ($request) {
                    $q2->where('name', 'like', '%' . $request->search . '%');
                });

            });
        }

        // 🏥 Filter by Specialty
        if ($request->specialty_id) {
            $query->where('specialty_id', $request->specialty_id);
        }

        // 🏥 Filter by Hospital
        if ($request->hospital_id) {
            $query->whereHas('chambers', function ($q) use ($request) {
                $q->where('hospital_id', $request->hospital_id);
            });
        }

        // 📍 Filter by District
        if ($request->district_id) {
            $query->whereHas('chambers.hospital', function ($q) use ($request) {
                $q->where('district_id', $request->district_id);
            });
        }

        // 🌍 Filter by Division
        if ($request->division_id) {
            $query->whereHas('chambers.hospital.district', function ($q) use ($request) {
                $q->where('division_id', $request->division_id);
            });
        }

        $perPage = $request->get('per_page', 10);
        return $query->paginate($perPage);
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