<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Specialization;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
 */
class DoctorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

         // نجيب IDs التخصصات
        $specializations = Specialization::pluck('id')->toArray();

        $date = now()->addDays(rand(1, 7))->format('Y-m-d');
        $slots = [];

        $start = Carbon::createFromFormat('Y-m-d H:i', $date . ' 09:00');
        $end   = Carbon::createFromFormat('Y-m-d H:i', $date . ' 13:00');

        while ($start < $end) {
            $slots[] = [
                'date' => $start->format('Y-m-d'),
                'from' => $start->format('H:i'),
                'to'   => $start->copy()->addMinutes(30)->format('H:i'),
            ];

            $start->addMinutes(30);
        }

        return [
            'specializations_id' => $this->faker->randomElement($specializations),
            'license_number' => $this->faker->bothify('LIC#####'),
            'session_price' => $this->faker->numberBetween(100, 500),
            'availability_slots' => $slots,
            'clinic_location' => [
                'lat' => 30.0444 + $this->faker->randomFloat(4, -0.1, 0.1),
                'lng' => 31.2357 + $this->faker->randomFloat(4, -0.1, 0.1),
                'address' => 'Cairo, Egypt',
            ],

        ];
    }
}
