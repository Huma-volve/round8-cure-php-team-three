<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // استدعاء Seeder الخاص بالتقييمات فقط
        $this->call(ReviewsSeeder::class);
    }
}
