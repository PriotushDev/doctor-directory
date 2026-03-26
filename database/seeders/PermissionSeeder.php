<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [

            'division.view',
            'division.create',
            'division.update',
            'division.delete',
            
            'district.view',
            'district.create',
            'district.update',
            'district.delete',
            
            'specialty.view',
            'specialty.create',
            'specialty.update',
            'specialty.delete',

            'doctor.view',
            'doctor.create',
            'doctor.update',
            'doctor.delete',

            'doctor_chamber.view',
            'doctor_chamber.create',
            'doctor_chamber.update',
            'doctor_chamber.delete',
            
            'hospital.view',
            'hospital.create',
            'hospital.update',
            'hospital.delete',

            'appointment.view',
            'appointment.create',
            'appointment.update',
            'appointment.delete',

            'appointment.view',
            'appointment.create',
            'appointment.update',
            'appointment.delete',

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
