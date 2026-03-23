<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'doctor_name' => $this->doctor?->name,
            'user_name' => $this->user?->name,
            'date' => $this->appointment_date,
            'time' => $this->appointment_time,
            'status' => $this->status,
            'notes' => $this->notes,
        ];
    }
}