<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AppointmentService;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AppointmentController extends Controller
{
    use AuthorizesRequests;

    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    /**
     * List appointments based on user role:
     * - Admin/Manager: all appointments
     * - Doctor: only their patients' appointments
     * - User: only their own appointments
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Appointment::with(['doctor', 'user', 'prescription']);

        // Role-based filtering
        if ($user->hasRole('admin') || $user->hasRole('manager')) {
            // Admin/Manager see everything — no filter needed
        } elseif ($user->hasRole('doctor')) {
            // Match by user_id first, then fallback to email if user_id is null
            $doctor = Doctor::where('user_id', $user->id)
                ->orWhere('email', $user->email)
                ->first();
                
            if ($doctor) {
                $query->where('doctor_id', $doctor->id);
            } else {
                // Doctor user has no linked Doctor profile — return empty
                return AppointmentResource::collection(collect([]));
            }
        } else {
            // Regular user — only their own appointments
            $query->where('user_id', $user->id);
        }

        // Optional filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        if ($request->filled('month')) {
            $query->whereMonth('appointment_date', $request->month);
        }

        if ($request->filled('year')) {
            $query->whereYear('appointment_date', $request->year);
        }

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        return AppointmentResource::collection(
            $query->latest()->paginate(10)
        );
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

    /**
     * Update appointment — admin or doctor can change status
     */
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