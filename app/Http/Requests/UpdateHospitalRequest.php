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
        return [
            'name' => 'sometimes|string|max:255',
            'district_id' => 'sometimes|exists:districts,id',
            'address' => 'sometimes|string|max:500',
            'phone' => 'sometimes|string|max:20|unique:hospitals,phone'
        ];
    }
}
