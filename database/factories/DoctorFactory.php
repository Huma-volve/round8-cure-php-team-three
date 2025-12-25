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
         // نجيب IDs موجودة من جدول Specializations
        $specializations = Specialization::pluck('id')->toArray();

        // نحدد تواريخ availability عشوائية (مثلاً 3 أيام من اليوم)
        $availability = [];
        for ($i = 1; $i <= 3; $i++) {
            $date = now()->addDays($i)->format('Y-m-d');
            $availability[] = [
                'date' => $date,
                'from' => $this->faker->time('H:i', '09:00'),
                'to' => $this->faker->time('H:i', '17:00'),
            ];
        }

        return [
            'name' => 'Dr. ' . $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'),
            'specializations_id' => $this->faker->randomElement($specializations),
            'mobile_number' => $this->faker->unique()->numerify('01#########'),
            'license_number' => $this->faker->unique()->bothify('LIC#####'),
            'session_price' => $this->faker->numberBetween(100, 500),
            'availability_slots' => $availability,
            'clinic_location' => [
                'lat' => 30.0444 + $this->faker->randomFloat(4, -0.05, 0.05),
                'lng' => 31.2357 + $this->faker->randomFloat(4, -0.05, 0.05),
                'address' => 'Cairo, Egypt',
            ],
        ];
    }
}
