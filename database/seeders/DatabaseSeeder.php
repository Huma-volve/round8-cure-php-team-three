<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Database\Seeders\SpecializationsSeeder;
use Database\Seeders\DoctorSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            SpecializationsSeeder::class,
        ]);

        // Create users directly via DB to avoid model trait dependencies
        $now = now();
        $patientId = DB::table('users')->insertGetId([
            'name' => 'Patient User',
            'email' => 'patient@example.com',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'email_verified_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $adminUserId = DB::table('users')->insertGetId([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'email_verified_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('admins')->insert([
            'user_id' => $adminUserId,
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'permissions' => 'all',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $doctorUserId = DB::table('users')->insertGetId([
            'name' => 'Doctor User',
            'email' => 'doctor@example.com',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'email_verified_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('doctors')->insert([
            'user_id' => $doctorUserId,
            'name' => 'Dr. Example',
            'email' => 'doctor@example.com',
            'password' => Hash::make('password'),
            'specializations_id' => 1,
            'mobile_number' => 1000000000,
            'license_number' => 'LIC00001',
            'session_price' => 150.0,
            'availability_slots' => json_encode([
                ['day' => 'Monday', 'from' => '09:00', 'to' => '17:00']
            ]),
            'clinic_location' => json_encode([
                'lat' => 30.0444,
                'lng' => 31.2357,
                'address' => 'Cairo, Egypt'
            ]),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

    }
}
