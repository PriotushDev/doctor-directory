<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;
use App\Enums\DayEnum;

class StoreDoctorChamberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // 🔥 MUST
    }

    public function rules(): array
    {
        return [
            'doctor_id'   => 'required|exists:doctors,id',
            'hospital_id' => 'required|exists:hospitals,id',
            'day'         => ['required', new Enum(DayEnum::class)],
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
            'fee'         => 'required|numeric|min:0'
        ];
    }
}
