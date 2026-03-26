<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDistrictRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // 🔥 VERY IMPORTANT
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'division_id' => 'required|exists:divisions,id'
        ];
    }
}