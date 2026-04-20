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
            'user_id' => $this->user_id,
            'name' => $this->name,
            'name_bn' => $this->name_bn,
            'slug' => $this->slug,
            'slug_bn' => $this->slug_bn,
            'specialty' => [
                'id' => $this->specialty?->id,
                'name' => $this->specialty?->name,
            ],
            'specialty_bn' => $this->specialty_bn,
            'hospital' => [
                'id' => $this->hospital?->id,
                'name' => $this->hospital?->name,
            ],
            'degree' => $this->degree,
            'degree_bn' => $this->degree_bn,
            'degree1' => $this->degree1,
            'degree1_bn' => $this->degree1_bn,
            'degree2' => $this->degree2,
            'degree2_bn' => $this->degree2_bn,
            'degree3' => $this->degree3,
            'degree3_bn' => $this->degree3_bn,
            'degree4' => $this->degree4,
            'degree4_bn' => $this->degree4_bn,
            'workplace' => $this->workplace,
            'workplace_bn' => $this->workplace_bn,
            'bmdc' => $this->bmdc,
            'fee' => $this->fee,
            'experience' => $this->experience,
            'phone' => $this->phone,
            'email' => $this->email,
            'bio' => $this->bio,
            'long_bio' => $this->long_bio,
            'photo' => $this->photo ? url('storage/' . $this->photo) : null,
        ];
    }
}