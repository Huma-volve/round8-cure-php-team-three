<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\SpecializationsSeeder;
use Database\Seeders\DoctorSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // استدعاء Seeder الخاص بالتقييمات فقط
        $this->call(ReviewsSeeder::class);
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

         $this->call([
        SpecializationsSeeder::class,
        DoctorSeeder::class,
    ]);
       
    }
}
