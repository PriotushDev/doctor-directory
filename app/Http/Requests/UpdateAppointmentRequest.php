<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'doctor_id' => 'sometimes|exists:doctors,id',
            'appointment_date' => 'sometimes|date',
            'appointment_time' => 'sometimes',
            'notes' => 'nullable|string',
            'status' => 'sometimes|in:pending,confirmed,completed,cancelled'
        ];
    }
}