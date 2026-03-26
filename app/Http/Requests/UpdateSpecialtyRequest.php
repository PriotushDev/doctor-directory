<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSpecialtyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // 🔥 MUST
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255|unique:specialties,name,' . $this->route('specialty'),
            'slug' => 'sometimes|string|max:255|unique:specialties,slug,' . $this->route('specialty'),
        ];
    }
}
