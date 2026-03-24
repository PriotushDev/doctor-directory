<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'specialty_id' => 'required|exists:specialties,id',
            'hospital_id' => 'required|exists:hospitals,id',
            'degree' => 'required|string',
            'experience' => 'required|integer',
            'phone' => 'required|string',
            'email' => 'required|email',
            'bio' => 'nullable|string'
        ];
    }
}