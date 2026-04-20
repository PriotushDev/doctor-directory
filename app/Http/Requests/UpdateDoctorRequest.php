<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');
        if (!$id) {
            $id = $this->route('doctor');
            if (is_object($id)) $id = $id->id;
        }

        return [
            'name' => 'sometimes|string|max:255',
            'name_bn' => 'nullable|string|max:255',
            'slug' => 'sometimes|string|max:255',
            'slug_bn' => 'nullable|string|max:255',
            'specialty_id' => 'sometimes|exists:specialties,id',
            'specialty_bn' => 'nullable|string|max:255',
            'hospital_id' => 'sometimes|exists:hospitals,id',
            'degree' => 'sometimes|string|max:255',
            'degree_bn' => 'nullable|string|max:255',
            'degree1' => 'nullable|string|max:255',
            'degree1_bn' => 'nullable|string|max:255',
            'degree2' => 'nullable|string|max:255',
            'degree2_bn' => 'nullable|string|max:255',
            'degree3' => 'nullable|string|max:255',
            'degree3_bn' => 'nullable|string|max:255',
            'degree4' => 'nullable|string|max:255',
            'degree4_bn' => 'nullable|string|max:255',
            'workplace'   => 'nullable|string|max:255',
            'workplace_bn'   => 'nullable|string|max:255',
            'bmdc'        => 'sometimes|string|unique:doctors,bmdc,' . $id,
            'fee'         => 'nullable|numeric|min:0',
            'experience'  => 'nullable|integer|min:0',
            'phone' => 'sometimes|string|unique:doctors,phone,' . $id,
            'email' => 'sometimes|email|unique:doctors,email,' . $id,
            'bio' => 'nullable|string',
            'long_bio' => 'nullable|string',
            'photo' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }
}