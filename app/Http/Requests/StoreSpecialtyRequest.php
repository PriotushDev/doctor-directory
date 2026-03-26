<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSpecialtyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // 🔥 MUST
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:specialties,name',
            'slug' => 'required|string|max:255|unique:specialties,slug'
        ];
    }
}