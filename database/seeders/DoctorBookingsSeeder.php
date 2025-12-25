<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Enums\BookingStatus;
use Illuminate\Support\Facades\DB;

class DoctorBookingsSeeder extends Seeder
{
    public function run()
    {
        $doctorId = 1;

        $users = User::take(1)->get();

        $statuses = ['Upcoming', 'Completed', 'Cancelled'];

        foreach ($users as $user) {
            for ($i = 0; $i < 5; $i++) {
                Booking::create([
                    'user_id' => $user->id,
                    'doctor_id' => $doctorId,
                    'booking_date' => now()->addDays(rand(0, 10))->toDateString(),
                    'booking_time' => now()->addHours(rand(8, 17))->format('H:i:s'),
                    'status' => $statuses[array_rand($statuses)],
                    'price' => rand(50, 200),
                    'payment_method_id' => 1,
                ]);
            }
        }
    }
}
