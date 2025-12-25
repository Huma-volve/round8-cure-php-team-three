<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AssignDoctorRolesSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = User::whereHas('doctor')->get();

        foreach ($doctors as $user) {
            if (!$user->hasRole('doctor')) {
                $user->assignRole('doctor');
                $this->command->info($user->email . " => role assigned");
            } else {
                $this->command->info($user->email . " => already has role");
            }
        }

        $this->command->info('All doctor roles have been checked and assigned.');
    }
}
