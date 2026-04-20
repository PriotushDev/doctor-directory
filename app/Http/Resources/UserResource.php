<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'patient_id' => $this->patient_id,
            'roles' => $this->roles->pluck('name'),
            'permissions' => $this->permissions->pluck('name'),
            'created_at' => $this->created_at,
        ];
    }
}
