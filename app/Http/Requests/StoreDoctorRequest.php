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
            'name'        => 'required|string|max:255',
            'name_bn'     => 'nullable|string|max:255',
            'slug'        => 'required|string|max:255|unique:doctors,slug',
            'slug_bn'     => 'nullable|string|max:255',
            'specialty_id'=> 'required|exists:specialties,id',
            'specialty_bn'=> 'nullable|string|max:255',
            'hospital_id' => 'nullable|exists:hospitals,id',
            'degree'      => 'nullable|string|max:255',
            'degree_bn'   => 'nullable|string|max:255',
            'degree1'     => 'nullable|string|max:255',
            'degree1_bn'  => 'nullable|string|max:255',
            'degree2'     => 'nullable|string|max:255',
            'degree2_bn'  => 'nullable|string|max:255',
            'degree3'     => 'nullable|string|max:255',
            'degree3_bn'  => 'nullable|string|max:255',
            'degree4'     => 'nullable|string|max:255',
            'degree4_bn'  => 'nullable|string|max:255',
            'workplace'   => 'nullable|string|max:255',
            'workplace_bn'=> 'nullable|string|max:255',
            'bmdc'        => 'required|string|unique:doctors,bmdc',
            'fee'         => 'nullable|numeric|min:0',
            'experience'  => 'nullable|integer|min:0',
            'phone'       => 'nullable|string|unique:doctors,phone',
            'email'       => 'nullable|email|unique:doctors,email',
            'bio'         => 'nullable|string',
            'long_bio'    => 'nullable|string',
            'photo'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}