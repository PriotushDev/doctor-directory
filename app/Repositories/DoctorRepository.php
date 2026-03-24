<?php

namespace App\Repositories;

use App\Models\Doctor;
use Illuminate\Support\Facades\Cache;

class DoctorRepository
{

use Illuminate\Support\Facades\Cache;

    public function getAll($request)
    {
        $cacheKey = 'doctors_' . md5(json_encode($request->all()));

        return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($request) {

            $query = Doctor::with(['specialty','hospital']);

            if ($request->filled('search')) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('degree', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                });
            }

            if ($request->filled('specialty_id')) {
                $query->where('specialty_id', $request->specialty_id);
            }

            if ($request->filled('hospital_id')) {
                $query->where('hospital_id', $request->hospital_id);
            }

            return $query->paginate(10);
        });
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