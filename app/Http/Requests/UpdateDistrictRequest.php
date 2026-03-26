<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDistrictRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // 🔥 MUST BE TRUE
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'division_id' => 'sometimes|exists:divisions,id'
        ];
    }
}