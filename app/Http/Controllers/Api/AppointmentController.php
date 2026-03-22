<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Services\AppointmentService;

class AppointmentController extends Controller
{
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;

        $this->middleware('permission:appointment.view')->only(['index','show']);
        $this->middleware('permission:appointment.create')->only('store');
        $this->middleware('permission:appointment.update')->only('update');
        $this->middleware('permission:appointment.delete')->only('destroy');
    }

    /**
    * Display a listing of the resource.
    */
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            $appointments = Appointment::with(['doctor','user'])->latest()->get();
        } else {
            $appointments = Appointment::with(['doctor'])
                ->where('user_id', $user->id)
                ->latest()
                ->get();
        }

        return response()->json($appointments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $appointment = Appointment::create([
            'doctor_id' => $request->doctor_id,
            'date' => $request->date,
            'time' => $request->time,
            'user_id' => auth()->id(), // 🔥 must
        ]);

        return response()->json([
            'message' => 'Appointment created',
            'data' => $appointment
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $appointment = Appointment::findOrFail($id);

        // 🔥 Policy check
        $this->authorize('view', $appointment);

        return response()->json($appointment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $appointment = $this->appointmentService->updateAppointment($id, $request);

        return response()->json([
            'status' => true,
            'message' => 'Appointment updated successfully',
            'data' => $appointment
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);

        // 🔥 Policy check
        $this->authorize('delete', $appointment);

        $appointment->delete();

        return response()->json([
            'message' => 'Appointment deleted successfully'
        ]);
    }   
}
