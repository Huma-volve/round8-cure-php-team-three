<?php

namespace Database\Factories;

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
        return [
            'user_id' => User::factory(),
            'specializations_id' => Specialization::inRandomOrder()->first()->id,
            'license_number' => $this->faker->unique()->bothify('LIC#####'),
            'session_price' => $this->faker->numberBetween(100, 500),
            'availability_slots' => [
                [
                    'day' => 'Monday',
                    'from' => '09:00',
                    'to' => '17:00',
                ]
            ],
            'clinic_location' => [
                'lat' => 30.0444 + $this->faker->randomFloat(4, -0.05, 0.05),
                'lng' => 31.2357 + $this->faker->randomFloat(4, -0.05, 0.05),
                'address' => 'Cairo, Egypt',
            ],

        ];
    }
}
