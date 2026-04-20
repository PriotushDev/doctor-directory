<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHospitalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // 🔥 MUST
    }

    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'name' => 'sometimes|string|max:255|unique:hospitals,name,' . $id,
            'district_id' => 'sometimes|exists:districts,id',
            'address' => 'sometimes|string|max:500',
            'phone' => 'sometimes|string|max:20|unique:hospitals,phone,' . $id,
            'email' => 'nullable|email|max:255',
            'url' => 'nullable|url|max:255',
            'photo' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'medical_test_list' => 'nullable|array',
            'medical_test_list.*' => 'string|max:255',
            'ambulance_number' => 'nullable|string|max:50',
            'reserved_doctor_number' => 'nullable|integer|min:0',
            'visited_doctor_number' => 'nullable|integer|min:0',
            'nurse_number' => 'nullable|integer|min:0',
            'staff_number' => 'nullable|integer|min:0',
            'ICU_number' => 'nullable|integer|min:0',
            'CCU_number' => 'nullable|integer|min:0',
            'HDU_number' => 'nullable|integer|min:0',
            'Cabin_number' => 'nullable|integer|min:0',
        ];
    }
}
