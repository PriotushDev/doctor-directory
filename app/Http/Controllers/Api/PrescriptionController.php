<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\PrescriptionMedicine;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Http\Resources\PrescriptionResource;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrescriptionController extends Controller
{
    /**
     * List prescriptions:
     * - Admin: all
     * - Doctor: only their own
     * - User: only their own (as patient)
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Prescription::with(['doctor', 'patient', 'appointment', 'medicines']);

        if ($user->hasRole('admin') || $user->hasRole('manager')) {
            // see all
        } elseif ($user->hasRole('doctor')) {
            $doctor = Doctor::where('user_id', $user->id)
                ->orWhere('email', $user->email)
                ->first();
                
            if ($doctor) {
                $query->where('doctor_id', $doctor->id);
            } else {
                return response()->json(['data' => []]);
            }
        } else {
            $query->where('patient_id', $user->id);
        }

        if ($request->filled('appointment_id')) {
            $query->where('appointment_id', $request->appointment_id);
        }

        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        return PrescriptionResource::collection(
            $query->latest()->paginate(20)
        );
    }

    /**
     * Create prescription for an appointment
     */
    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'diagnosis' => 'required|string',
            'advice' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
            'cc' => 'nullable|string',
            'oe' => 'nullable|string',
            'oh' => 'nullable|string',
            'mh' => 'nullable|string',
            'investigation' => 'nullable|string',
            'age' => 'nullable|string',
            'sex' => 'nullable|string',
            'weight' => 'nullable|string',
            'registration_no' => 'nullable|string',
            'medicines' => 'required|array|min:1',
            'medicines.*.medicine_name' => 'required|string',
            'medicines.*.dosage' => 'nullable|string',
            'medicines.*.duration' => 'nullable|string',
            'medicines.*.instructions' => 'nullable|string',
        ]);

        $user = auth()->user();
        $doctor = Doctor::where('user_id', $user->id)
            ->orWhere('email', $user->email)
            ->first();

        if (!$doctor) {
            return response()->json(['message' => 'Doctor profile not found'], 403);
        }

        $appointment = Appointment::findOrFail($request->appointment_id);

        // Verify this appointment belongs to this doctor
        if ((int)$appointment->doctor_id !== (int)$doctor->id) {
            return response()->json(['message' => 'This appointment does not belong to you'], 403);
        }

        $prescription = DB::transaction(function () use ($request, $doctor, $appointment) {
            $prescription = Prescription::create([
                'appointment_id' => $appointment->id,
                'doctor_id' => $doctor->id,
                'patient_id' => $appointment->user_id,
                'diagnosis' => $request->diagnosis,
                'advice' => $request->advice,
                'follow_up_date' => $request->follow_up_date,
                'cc' => $request->cc,
                'oe' => $request->oe,
                'oh' => $request->oh,
                'mh' => $request->mh,
                'investigation' => $request->investigation,
                'age' => $request->age,
                'sex' => $request->sex,
                'weight' => $request->weight,
                'registration_no' => $request->registration_no,
            ]);

            foreach ($request->medicines as $med) {
                // Auto-save to medicines table if unique
                Medicine::firstOrCreate(['name' => $med['medicine_name']]);

                PrescriptionMedicine::create([
                    'prescription_id' => $prescription->id,
                    'medicine_name' => $med['medicine_name'],
                    'dosage' => $med['dosage'] ?? null,
                    'duration' => $med['duration'] ?? null,
                    'instructions' => $med['instructions'] ?? null,
                ]);
            }

            return $prescription;
        });

        return new PrescriptionResource($prescription->load(['doctor', 'patient', 'appointment', 'medicines']));
    }

    /**
     * Show a single prescription
     */
    public function show($id)
    {
        $prescription = Prescription::with(['doctor', 'patient', 'appointment', 'medicines'])->findOrFail($id);

        return new PrescriptionResource($prescription);
    }

    /**
     * Update a prescription
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'diagnosis' => 'sometimes|string',
            'advice' => 'nullable|string',
            'follow_up_date' => 'nullable|date',
            'cc' => 'nullable|string',
            'oe' => 'nullable|string',
            'oh' => 'nullable|string',
            'mh' => 'nullable|string',
            'investigation' => 'nullable|string',
            'age' => 'nullable|string',
            'sex' => 'nullable|string',
            'weight' => 'nullable|string',
            'registration_no' => 'nullable|string',
            'medicines' => 'sometimes|array|min:1',
            'medicines.*.medicine_name' => 'required|string',
            'medicines.*.dosage' => 'nullable|string',
            'medicines.*.duration' => 'nullable|string',
            'medicines.*.instructions' => 'nullable|string',
        ]);

        $prescription = Prescription::findOrFail($id);

        DB::transaction(function () use ($request, $prescription) {
            $prescription->update($request->only([
                'diagnosis', 'advice', 'follow_up_date',
                'cc', 'oe', 'oh', 'mh', 'investigation',
                'age', 'sex', 'weight', 'registration_no'
            ]));

            if ($request->has('medicines')) {
                // Delete old medicines and replace
                $prescription->medicines()->delete();
                foreach ($request->medicines as $med) {
                    Medicine::firstOrCreate(['name' => $med['medicine_name']]);

                    PrescriptionMedicine::create([
                        'prescription_id' => $prescription->id,
                        'medicine_name' => $med['medicine_name'],
                        'dosage' => $med['dosage'] ?? null,
                        'duration' => $med['duration'] ?? null,
                        'instructions' => $med['instructions'] ?? null,
                    ]);
                }
            }
        });

        return new PrescriptionResource($prescription->load(['doctor', 'patient', 'appointment', 'medicines']));
    }

    /**
     * Delete a prescription
     */
    public function destroy($id)
    {
        $prescription = Prescription::findOrFail($id);
        $prescription->delete();

        return response()->json(['message' => 'Prescription deleted successfully']);
    }
}
