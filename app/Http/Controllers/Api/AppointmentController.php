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
        $appointments = $this->appointmentService->listAppointments();

        return response()->json([
            'status' => true,
            'message' => 'Appointment Create successfully',
            'data' => $appointments
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $appointments = $this->appointmentService->createAppointment($request);

        return response()->json([
            'status' => true,
            'message' => 'Appointment Create successfully',
            'data' => $appointments
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $appointment = $this->appointmentService->getAppointmentById($id);

        return response()->json([
            'status' => true,
            'data' => $appointment
        ]);
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
        $this->appointmentService->deleteAppointment($id);

        return response()->json([
            'status' => true,
            'message' => 'Appointment deleted successfully'
        ]);
    }
}
