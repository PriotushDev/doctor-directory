<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DoctorService;
use Illuminate\Http\Request;
use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Http\Resources\DoctorResource;

use App\Models\Doctor;

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
        $user = auth()->user();

        // If not admin/manager, check if they are a doctor creating their own profile
        if (!$user->can('doctor.create') && !$user->hasRole('admin') && !$user->hasRole('manager')) {
            if (!$user->hasRole('doctor')) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            // A doctor can only create one profile for themselves
            $existing = Doctor::where('user_id', $user->id)->first();
            if ($existing) {
                return response()->json(['message' => 'You already have a doctor profile.'], 400);
            }

            $data = $request->validated();
            $data['user_id'] = $user->id;
            $doctor = $this->doctorService->createDoctor($data);
        } else {
            // Admin/Manager can create for any user
            $doctor = $this->doctorService->createDoctor($request->validated());
        }

        return new DoctorResource($doctor);
    }

    public function show($id)
    {
        $doctor = $this->doctorService->getDoctor($id);

        return new DoctorResource($doctor);
    }

    public function update(UpdateDoctorRequest $request, $id)
    {
        $doctor = $this->doctorService->getDoctor($id);
        $user = auth()->user();

        // If not admin/manager, check ownership
        if (!$user->can('doctor.update') && !$user->hasRole('admin') && !$user->hasRole('manager')) {
            $isOwner = ((int)$doctor->user_id === (int)$user->id);
            
            // Fallback: Check Email matching if user_id is missing or doesn't match
            if (!$isOwner && $doctor->email === $user->email) {
                // Auto-link the doctor to this user account
                $doctor->update(['user_id' => $user->id]);
                $isOwner = true;
            }

            if (!$user->hasRole('doctor') || !$isOwner) {
                return response()->json(['message' => 'You are only allowed to update your own profile.'], 403);
            }
        }

        $updatedDoctor = $this->doctorService->updateDoctor($id, $request->validated());

        return new DoctorResource($updatedDoctor);
    }

    public function destroy($id)
    {
        $this->doctorService->deleteDoctor($id);

        return response()->json([
            'message' => 'Doctor deleted successfully'
        ]);
    }
}