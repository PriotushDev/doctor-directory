<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $doctor = Role::firstOrCreate(['name' => 'doctor']);
        $user = Role::firstOrCreate(['name' => 'user']);

        $admin->syncPermissions(Permission::all());

        // Manager: hospital authority — create + edit (no delete)
        $manager->syncPermissions([
            'hospital.view',
            'hospital.create',
            'hospital.update',
            'doctor.view',
            'doctor.create',
            'doctor.update',
            'doctor_chamber.view',
            'doctor_chamber.create',
            'doctor_chamber.update',
            'appointment.view',
        ]);

        // Doctor: own profile + chambers + appointment status changes + medicine management
        $doctor->syncPermissions([
            'doctor.view',
            'doctor_chamber.view',
            'doctor_chamber.create',
            'doctor_chamber.update',
            'appointment.view',
            'appointment.update',
            'medicine.view',
            'medicine.create',
            'medicine.update',
        ]);

        $user->syncPermissions([
            'doctor.view',
            'appointment.create',
            'appointment.view',
        ]);
    }
}
