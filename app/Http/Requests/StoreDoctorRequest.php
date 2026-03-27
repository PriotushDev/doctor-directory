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
            'name' => 'required|string|max:255',
            'specialty_id' => 'required|exists:specialties,id',
            'hospital_id' => 'required|exists:hospitals,id',
            'degree' => 'required|string|max:255',
            'experience' => 'required|integer|min:0',
            'phone' => 'required|string|unique:doctors,phone',
            'email' => 'required|email|unique:doctors,email',
            'bio' => 'nullable|string'
        ];
    }
}