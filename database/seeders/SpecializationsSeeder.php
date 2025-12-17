<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecializationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('specializations')->insert([
            ['name' => 'Cardiology'],
            ['name' => 'Dermatology'],
            ['name' => 'Pediatrics'],
            ['name' => 'Neurology'],
            ['name' => 'Orthopedics'],
        ]);
    }
}
