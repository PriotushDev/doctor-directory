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
        $id = $this->route('id');
        return [
            'doctor_id'   => [
                'sometimes',
                'exists:doctors,id',
                \Illuminate\Validation\Rule::unique('doctor_chambers')
                    ->where('hospital_id', $this->hospital_id)
                    ->where('day', $this->day)
                    ->ignore($id)
            ],
            'hospital_id' => 'sometimes|exists:hospitals,id',
            'day'         => ['required', new Enum(DayEnum::class)],
            'start_time'  => 'sometimes|string',
            'end_time'    => 'sometimes|string',
            'fee'         => 'sometimes|numeric|min:0'
        ];
    }
}
