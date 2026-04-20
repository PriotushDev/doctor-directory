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
            'doctor_name_bn' => $this->doctor?->name_bn,
            'doctor_degree' => $this->doctor?->degree,
            'doctor_degree_bn' => $this->doctor?->degree_bn,
            'doctor_specialty' => $this->doctor?->specialty?->name,
            'doctor_specialty_bn' => $this->doctor?->specialty_bn,
            'doctor_degree1' => $this->doctor?->degree1,
            'doctor_degree1_bn' => $this->doctor?->degree1_bn,
            'doctor_degree2' => $this->doctor?->degree2,
            'doctor_degree2_bn' => $this->doctor?->degree2_bn,
            'doctor_degree3' => $this->doctor?->degree3,
            'doctor_degree3_bn' => $this->doctor?->degree3_bn,
            'doctor_degree4' => $this->doctor?->degree4,
            'doctor_degree4_bn' => $this->doctor?->degree4_bn,
            'doctor_workplace' => $this->doctor?->workplace,
            'doctor_workplace_bn' => $this->doctor?->workplace_bn,
            'doctor_bmdc' => $this->doctor?->bmdc,
            'doctor_slug' => $this->doctor?->slug,
            'doctor_slug_bn' => $this->doctor?->slug_bn,
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
            
            // Hospital/Chamber Information with Fallbacks
            'chamber_name' => $this->appointment?->chamber?->hospital?->name 
                           ?? $this->doctor?->hospital?->name 
                           ?? $this->doctor?->chambers?->first()?->hospital?->name 
                           ?? 'MedConnect Partner Hospital',
            
            'chamber_address' => $this->appointment?->chamber?->hospital?->address 
                              ?? $this->doctor?->hospital?->address 
                              ?? $this->doctor?->chambers?->first()?->hospital?->address,
            
            'chamber_phone' => $this->appointment?->chamber?->hospital?->phone 
                            ?? $this->doctor?->hospital?->phone 
                            ?? $this->doctor?->chambers?->first()?->hospital?->phone,
            
            'chamber_email' => $this->appointment?->chamber?->hospital?->email 
                            ?? $this->doctor?->hospital?->email 
                            ?? $this->doctor?->chambers?->first()?->hospital?->email,

            'hospital_name' => $this->appointment?->chamber?->hospital?->name 
                            ?? $this->doctor?->hospital?->name,
            
            'hospital_address' => $this->appointment?->chamber?->hospital?->address 
                               ?? $this->doctor?->hospital?->address,
            
            'hospital_phone' => $this->appointment?->chamber?->hospital?->phone 
                             ?? $this->doctor?->hospital?->phone,
            
            'hospital_email' => $this->appointment?->chamber?->hospital?->email 
                             ?? $this->doctor?->hospital?->email,

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
