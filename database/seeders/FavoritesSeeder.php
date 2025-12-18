<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Favorite;
use Illuminate\Database\Seeder;

class FavoritesSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();

        if (!$user) {
            $user = User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        $doctors = Doctor::factory()->count(5)->create();

        $hospitals = Hospital::factory()->count(3)->create();

        foreach ($doctors->take(3) as $doctor) {
            Favorite::create([
                'user_id' => $user->id,
                'favorable_id' => $doctor->id,
                'favorable_type' => Doctor::class,
                'type' => 'doctor'
            ]);
        }

        foreach ($hospitals->take(2) as $hospital) {
            Favorite::create([
                'user_id' => $user->id,
                'favorable_id' => $hospital->id,
                'favorable_type' => Hospital::class,
                'type' => 'hospital'
            ]);
        }
    }
}
