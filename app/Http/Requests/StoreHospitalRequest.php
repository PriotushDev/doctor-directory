<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHospitalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // 🔥 MUST
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20'
        ];
    }
}
