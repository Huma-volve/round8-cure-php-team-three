<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions 
        Permission::firstOrCreate(['name' => 'manage users']);
        Permission::firstOrCreate(['name' => 'manage doctors']);
        Permission::firstOrCreate(['name' => 'manage patients']);

        // Create Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $doctorRole = Role::firstOrCreate(['name' => 'doctor']);
        $patientRole = Role::firstOrCreate(['name' => 'patient']);
        // $helperRole = Role::firstOrCreate(['name' => 'helper']);

        // Assign Permissions to Roles 
        $adminRole->givePermissionTo(['manage users', 'manage doctors', 'manage patients']);
        $doctorRole->givePermissionTo(['manage patients']);
        

        $this->command->info('Roles and Permissions seeded successfully!');

        
    }
}
