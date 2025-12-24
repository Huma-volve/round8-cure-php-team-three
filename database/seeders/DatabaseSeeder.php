<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            SpecializationsSeeder::class,
            DoctorSeeder::class,
            AssignDoctorRolesSeeder::class,
        ]);
    }
}
