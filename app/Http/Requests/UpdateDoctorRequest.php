<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string',
            'specialty_id' => 'sometimes|exists:specialties,id',
            'hospital_id' => 'sometimes|exists:hospitals,id',
            'degree' => 'sometimes|string',
            'experience' => 'sometimes|string',
            'phone' => 'sometimes|string',
            'email' => 'sometimes|email',
            'bio' => 'nullable|string'
        ];
    }
}