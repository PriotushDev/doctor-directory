<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'doctor_id' => 'required|exists:doctors,id',
            'chamber_id' => 'nullable|exists:doctor_chambers,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'status' => 'sometimes|in:pending,confirmed,completed,cancelled',
            'notes' => 'nullable|string',
            'payment_status' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'payment_number' => 'nullable|string',
            'transaction_id' => 'nullable|string',
            'amount' => 'nullable|numeric',
        ];
    }
}