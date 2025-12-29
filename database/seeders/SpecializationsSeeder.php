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
        [
            'name' => 'Cardiology',
            'image' => 'https://i.pinimg.com/1200x/16/e5/5b/16e55bba61d9dd99383b137215dd13bb.jpg',
        ],
        [
            'name' => 'Dermatology',
            'image' => 'https://i.pinimg.com/736x/65/02/f4/6502f4f55ea701fa859ee1be63f3b84e.jpg',
        ],
        [
            'name' => 'Pediatrics',
            'image' => 'https://i.pinimg.com/1200x/f0/7d/6c/f07d6c3299d53f64b121d4370d70a470.jpg',
        ],
        [
            'name' => 'Neurology',
            'image' => 'https://i.pinimg.com/736x/ef/d7/43/efd7431214fa0c2a7175cb242d02e5f6.jpg', 
        ],
        [
            'name' => 'Orthopedics',
            'image' => 'https://i.pinimg.com/736x/ba/61/ea/ba61ea33a4d5946dc7917b3ec100e73b.jpg',
        ],
    ]);
    }
}
