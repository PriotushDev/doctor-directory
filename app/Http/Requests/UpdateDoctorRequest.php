<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');

        return [
            'name' => 'sometimes|string|max:255',
            'specialty_id' => 'sometimes|exists:specialties,id',
            'hospital_id' => 'sometimes|exists:hospitals,id',
            'degree' => 'sometimes|string|max:255',
            'experience' => 'sometimes|integer|min:0',

            'phone' => [
                'sometimes',
                Rule::unique('doctors', 'phone')->ignore($id)
            ],

            'email' => [
                'sometimes',
                'email',
                Rule::unique('doctors', 'email')->ignore($id)
            ],

            'bio' => 'nullable|string'
        ];
    }
}