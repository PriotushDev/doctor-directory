<?php

namespace App\Services;

use App\Repositories\DoctorRepository;
use Illuminate\Support\Facades\Cache;

class DoctorService
{
    protected $doctorRepository;

    public function __construct(DoctorRepository $doctorRepository)
    {
        $this->doctorRepository = $doctorRepository;
    }

    public function listDoctors($request)
    {
        return $this->doctorRepository->getAll($request);
    }

    public function createDoctor(array $data)
    {
        Cache::flush(); // clear cache
        return $this->doctorRepository->create($data);
    }

    public function getDoctor($id)
    {
        return $this->doctorRepository->findById($id);
    }

    public function updateDoctor($id, array $data)
    {
        Cache::flush(); // clear cache
        return $this->doctorRepository->update($id, $data);
    }

    public function deleteDoctor($id)
    {
        Cache::flush(); // clear cache
        return $this->doctorRepository->delete($id);
    }
}