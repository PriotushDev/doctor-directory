<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorChamberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // 🔥 MUST
    }

    public function rules(): array
    {
        return [
            'doctor_id'   => 'sometimes|exists:doctors,id',
            'hospital_id' => 'sometimes|exists:hospitals,id',
            'day'         => 'sometimes|string|max:50',
            'start_time'  => 'sometimes|date_format:H:i',
            'end_time'    => 'sometimes|date_format:H:i|after:start_time',
            'fee'         => 'sometimes|numeric|min:0'
        ];
    }
}
