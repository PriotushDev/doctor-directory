<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DoctorService;
use Illuminate\Http\Request;
use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Http\Resources\DoctorResource;

class DoctorController extends Controller
{
    protected $doctorService;

    public function __construct(DoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }

    public function index(Request $request)
    {
        $doctors = $this->doctorService->listDoctors($request);

        return DoctorResource::collection($doctors);
    }

    public function store(StoreDoctorRequest $request)
    {
        $doctor = $this->doctorService->createDoctor($request->validated());

        return new DoctorResource($doctor);
    }

    public function show($id)
    {
        $doctor = $this->doctorService->getDoctor($id);

        return new DoctorResource($doctor);
    }

    public function update(UpdateDoctorRequest $request, $id)
    {
        $doctor = $this->doctorService->updateDoctor($id, $request->validated());

        return new DoctorResource($doctor);
    }

    public function destroy($id)
    {
        $this->doctorService->deleteDoctor($id);

        return response()->json([
            'message' => 'Doctor deleted successfully'
        ]);
    }
}