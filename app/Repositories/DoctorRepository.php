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
        return $query->latest()->paginate($perPage);
    }

    protected function handlePhotoUpload($data, $existingPhoto = null)
    {
        if (isset($data['photo']) && $data['photo'] instanceof \Illuminate\Http\UploadedFile) {
            // Delete old photo if it exists
            if ($existingPhoto && \Illuminate\Support\Facades\Storage::disk('public')->exists($existingPhoto)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($existingPhoto);
            }
            // Store new photo
            $data['photo'] = $data['photo']->store('doctors', 'public');
        } else {
            // If it's a string (e.g. full URL from frontend) or empty, ignore it to prevent corrupting the relative DB path
            unset($data['photo']);
        }
        return $data;
    }

    public function create(array $data)
    {
        $data = $this->handlePhotoUpload($data);
        return Doctor::create($data);
    }

    public function findById($id)
    {
        return Doctor::with(['hospital','specialty','chambers'])->findOrFail($id);
    }

    public function update($id, array $data)
    {
        $doctor = $this->findById($id);
        $data = $this->handlePhotoUpload($data, $doctor->photo);
        $doctor->update($data);
        return $doctor;
    }

    public function delete($id)
    {
        $doctor = $this->findById($id);
        return $doctor->delete();
    }
}