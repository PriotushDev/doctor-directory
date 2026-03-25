<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Repositories\AppointmentRepository;
use App\Models\Appointment;
use App\Events\AppointmentCreated;
use App\Exceptions\BookingException;
use App\Models\DoctorChamber;
use Carbon\Carbon;

class AppointmentService
{
    protected $appointmentRepository;

    public function __construct(AppointmentRepository $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    public function listAppointments()
    {
        return $this->appointmentRepository->getAll();
    }


    public function createAppointment(array $data)
    {
        if (!auth()->check()) {
            throw new \Exception('User not authenticated');
        }

        return DB::transaction(function () use ($data) {

            $userId = auth()->id();

            // 🔥 Get day from date
            $day = Carbon::parse($data['appointment_date'])->format('l');

            // 🔥 Lock chamber row (optional but safe)
            $chamber = DoctorChamber::where('doctor_id', $data['doctor_id'])
                ->where('day', $day)
                ->lockForUpdate()
                ->first();

            if (!$chamber) {
                throw new BookingException('Doctor is not available on this day');
            }

            // 🔥 Check time range
            if (
                $data['appointment_time'] < $chamber->start_time ||
                $data['appointment_time'] > $chamber->end_time
            ) {
                throw new BookingException('Selected time is outside doctor schedule');
            }

            // 🔥 LOCK existing appointments for this slot
            $existing = Appointment::where('doctor_id', $data['doctor_id'])
                ->where('appointment_date', $data['appointment_date'])
                ->where('appointment_time', $data['appointment_time'])
                ->lockForUpdate()
                ->exists();

            if ($existing) {
                throw new BookingException('This time slot is already booked');
            }

            // 🔥 Prevent same user duplicate
            $userExists = Appointment::where('user_id', $userId)
                ->where('doctor_id', $data['doctor_id'])
                ->where('appointment_date', $data['appointment_date'])
                ->exists();

            if ($userExists) {
                throw new BookingException('You already booked this doctor for this date');
            }

            // ✅ Create appointment
            $data['user_id'] = $userId;
            $data['status'] = 'pending';

            $appointment = $this->appointmentRepository->create($data);

            event(new AppointmentCreated($appointment));

            return $appointment;
        });
    }

    public function getAppointmentById($id)
    {
        return $this->appointmentRepository->findById($id);
    }

    public function updateAppointment($id, array $data)
    {
        $appointment = $this->appointmentRepository->findById($id);

        // 🔥 Prevent double booking when updating
        if (isset($data['doctor_id']) || isset($data['appointment_date']) || isset($data['appointment_time'])) {

            $doctorId = $data['doctor_id'] ?? $appointment->doctor_id;
            $date = $data['appointment_date'] ?? $appointment->appointment_date;
            $time = $data['appointment_time'] ?? $appointment->appointment_time;

            $exists = Appointment::where('doctor_id', $doctorId)
                ->where('appointment_date', $date)
                ->where('appointment_time', $time)
                ->where('id', '!=', $id)
                ->exists();

            if ($exists) {
                throw new BookingException('This time slot is already booked');
            }
        }

        return $this->appointmentRepository->update($id, $data);
    }



    public function deleteAppointment($id)
    {
        return $this->appointmentRepository->delete($id);
    }
}