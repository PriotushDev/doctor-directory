<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrescriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'appointment_id' => $this->appointment_id,
            'doctor_id' => $this->doctor_id,
            'patient_id' => $this->patient_id,
            'doctor_name' => $this->doctor?->name,
            'doctor_degree' => $this->doctor?->degree,
            'doctor_specialty' => $this->doctor?->specialty?->name,
            'doctor_phone' => $this->doctor?->phone,
            'doctor_email' => $this->doctor?->email,
            'patient_name' => $this->patient?->name,
            'patient_email' => $this->patient?->email,
            'appointment_date' => $this->appointment?->appointment_date,
            'appointment_time' => $this->appointment?->appointment_time,
            'diagnosis' => $this->diagnosis,
            'advice' => $this->advice,
            'follow_up_date' => $this->follow_up_date?->format('Y-m-d'),
            
            // New fields
            'cc' => $this->cc,
            'oe' => $this->oe,
            'oh' => $this->oh,
            'mh' => $this->mh,
            'investigation' => $this->investigation,
            'age' => $this->age,
            'sex' => $this->sex,
            'weight' => $this->weight,
            'registration_no' => $this->registration_no,
            
            // Hospital Information via Appointment Chamber
            'hospital_name' => $this->appointment?->chamber?->hospital?->name,
            'hospital_address' => $this->appointment?->chamber?->hospital?->address,
            'chamber_name' => $this->appointment?->chamber?->chamber_name,

            'medicines' => $this->medicines->map(fn($m) => [
                'id' => $m->id,
                'medicine_name' => $m->medicine_name,
                'dosage' => $m->dosage,
                'duration' => $m->duration,
                'instructions' => $m->instructions,
            ]),
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
