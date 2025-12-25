<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->count(20)->create()->each(function ($user) {
            // Assign role doctor
            $user->assignRole('doctor');

            // Create doctor linked to this user
            Doctor::factory()->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
