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
        $admin = Role::create(['name' => 'admin']);
        $manager = Role::create(['name' => 'manager']);
        $doctor = Role::create(['name' => 'doctor']);
        $user = Role::create(['name' => 'user']);

        $admin->givePermissionTo(Permission::all());

        $doctor->givePermissionTo([
            'doctor.view',
            'appointment.view'
        ]);

        $user->givePermissionTo([
            'doctor.view',
            'appointment.create'
        ]);
    }
}
