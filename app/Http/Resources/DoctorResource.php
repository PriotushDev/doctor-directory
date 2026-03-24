<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'specialty' => $this->specialty?->name,
            'hospital' => $this->hospital?->name,
            'degree' => $this->degree,
            'experience' => $this->experience,
            'phone' => $this->phone,
            'email' => $this->email,
        ];
    }
}