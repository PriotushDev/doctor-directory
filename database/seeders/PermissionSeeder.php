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

            'medicine.view',
            'medicine.create',
            'medicine.update',
            'medicine.delete',
            
            'upazila.view',
            'upazila.create',
            'upazila.update',
            'upazila.delete',

            'union.view',
            'union.create',
            'union.update',
            'union.delete',
            
            'user.view',
            'user.update',
            'user.delete',
            'user.permission',

            'prescription.view',
            'prescription.create',
            'prescription.update',
            'prescription.delete',

            'payment.view',
            'payment.update',
            'payment.delete',

            'content.update'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
