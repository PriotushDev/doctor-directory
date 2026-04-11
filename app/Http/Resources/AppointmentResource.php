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
            'doctor_id' => $this->doctor_id,
            'user_id' => $this->user_id,
            'doctor_name' => $this->doctor?->name,
            'doctor_email' => $this->doctor?->email,
            'user_name' => $this->user?->name,
            'user_email' => $this->user?->email,
            'date' => $this->appointment_date,
            'time' => $this->appointment_time,
            'status' => $this->status,
            'notes' => $this->notes,
            'chamber_id' => $this->chamber_id,
            'payment_status' => $this->payment_status,
            'prescription_id' => $this->prescription?->id,
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}