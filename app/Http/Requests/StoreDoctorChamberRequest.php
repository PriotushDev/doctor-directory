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
            'doctor_id'   => [
                'required',
                'exists:doctors,id',
                \Illuminate\Validation\Rule::unique('doctor_chambers')
                    ->where('hospital_id', $this->hospital_id)
                    ->where('day', $this->day)
            ],
            'hospital_id' => 'required|exists:hospitals,id',
            'day'         => ['required', new Enum(DayEnum::class)],
            'start_time'  => 'required|string',
            'end_time'    => 'required|string',
            'fee'         => 'required|numeric|min:0'
        ];
    }
}
