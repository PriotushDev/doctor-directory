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

            'doctor.view',
            'doctor.create',
            'doctor.update',
            'doctor.delete',

            'hospital.view',
            'hospital.create',
            'hospital.update',
            'hospital.delete',

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
