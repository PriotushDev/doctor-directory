<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AppointmentService;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AppointmentController extends Controller
{
    use AuthorizesRequests;

    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function index()
    {
        $appointments = $this->appointmentService->listAppointments();

        return AppointmentResource::collection($appointments);
    }

    public function store(StoreAppointmentRequest $request)
    {
        $appointment = $this->appointmentService->createAppointment($request->validated());

        return new AppointmentResource($appointment);
    }

    public function show($id)
    {
        $appointment = $this->appointmentService->getAppointmentById($id);

        $this->authorize('view', $appointment);

        return new AppointmentResource($appointment);
    }

    public function update(UpdateAppointmentRequest $request, $id)
    {
        $appointment = $this->appointmentService->getAppointmentById($id);

        $this->authorize('update', $appointment);

        $updated = $this->appointmentService->updateAppointment($id, $request->validated());

        return new AppointmentResource($updated);
    }

    public function destroy($id)
    {
        $appointment = $this->appointmentService->getAppointmentById($id);

        $this->authorize('delete', $appointment);

        $this->appointmentService->deleteAppointment($id);

        return response()->json([
            'message' => 'Appointment deleted successfully'
        ]);
    }
}