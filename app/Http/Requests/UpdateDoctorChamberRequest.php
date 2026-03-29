<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\DayEnum;

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
            'day'         => ['required', new Enum(DayEnum::class)],
            'start_time'  => 'sometimes|date_format:H:i',
            'end_time'    => 'sometimes|date_format:H:i|after:start_time',
            'fee'         => 'sometimes|numeric|min:0'
        ];
    }
}
