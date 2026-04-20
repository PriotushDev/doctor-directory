<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WalkInPatientController extends Controller
{
    /**
     * Create a new walk-in patient and a linked appointment simultaneously
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
        ]);

        $authUser = auth()->user();
        
        // Find doctor profile
        $doctor = Doctor::where('user_id', $authUser->id)
            ->orWhere('email', $authUser->email)
            ->first();

        if (!$doctor) {
            return response()->json(['message' => 'Doctor profile not found'], 403);
        }

        $result = DB::transaction(function () use ($request, $doctor) {
            // Generate unique 8 digit registration number
            do {
                $patientId = random_int(10000000, 99999999);
            } while (User::where('patient_id', $patientId)->exists());

            $password = \Illuminate\Support\Str::random(8); // random password

            $patient = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($password),
                'patient_id' => $patientId,
                'role' => 'user'
            ]);

            // 2. Create Dummy Appointment
            $appointment = Appointment::create([
                'doctor_id' => $doctor->id,
                'user_id' => $patient->id,
                'chamber_id' => null, // Walk-in
                'appointment_date' => Carbon::today()->format('Y-m-d'),
                'appointment_time' => Carbon::now()->format('H:i'),
                'status' => 'completed',
                'payment_status' => 'Paid',
                'notes' => 'Direct Walk-In Patient (Registered by Doctor)',
            ]);

            return [
                'patient' => $patient,
                'appointment' => $appointment,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Patient registered successfully',
            'data' => [
                'appointment_id' => $result['appointment']->id,
                'patient_name' => $result['patient']->name,
                'patient_email' => $result['patient']->email,
            ]
        ]);
    }
}
