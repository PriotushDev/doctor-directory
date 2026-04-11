<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Specialty;
use App\Models\Division;
use App\Models\District;
use App\Models\DoctorChamber;

class AdminController extends Controller
{
    /**
     * Return dashboard statistics based on user role.
     */
    public function stats()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return response()->json([
                'success' => true,
                'data' => [
                    'total_doctors' => Doctor::count(),
                    'total_hospitals' => Hospital::count(),
                    'total_specialties' => Specialty::count(),
                    'total_divisions' => Division::count(),
                    'total_districts' => District::count(),
                    'total_chambers' => DoctorChamber::count(),
                    'total_users' => User::count(),
                    'total_appointments' => Appointment::count(),
                    'pending_appointments' => Appointment::where('status', 'pending')->count(),
                    'confirmed_appointments' => Appointment::where('status', 'confirmed')->count(),
                    'completed_appointments' => Appointment::where('status', 'completed')->count(),
                    'cancelled_appointments' => Appointment::where('status', 'cancelled')->count(),
                ]
            ]);
        }

        if ($user->hasRole('manager')) {
            return response()->json([
                'success' => true,
                'data' => [
                    'total_doctors' => Doctor::count(),
                    'total_hospitals' => Hospital::count(),
                    'total_chambers' => DoctorChamber::count(),
                    'total_appointments' => Appointment::count(),
                    'pending_appointments' => Appointment::where('status', 'pending')->count(),
                ]
            ]);
        }

        if ($user->hasRole('doctor')) {
            $doctor = Doctor::where('user_id', $user->id)
                ->orWhere('email', $user->email)
                ->first();
            $doctorId = $doctor ? $doctor->id : 0;

            return response()->json([
                'success' => true,
                'data' => [
                    'doctor_id' => $doctorId,
                    'total_appointments' => Appointment::where('doctor_id', $doctorId)->count(),
                    'pending_appointments' => Appointment::where('doctor_id', $doctorId)->where('status', 'pending')->count(),
                    'confirmed_appointments' => Appointment::where('doctor_id', $doctorId)->where('status', 'confirmed')->count(),
                    'completed_appointments' => Appointment::where('doctor_id', $doctorId)->where('status', 'completed')->count(),
                    'cancelled_appointments' => Appointment::where('doctor_id', $doctorId)->where('status', 'cancelled')->count(),
                    'total_chambers' => DoctorChamber::where('doctor_id', $doctorId)->count(),
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 403);
    }
}
