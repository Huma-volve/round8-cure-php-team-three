<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'specialization' => $this->faker->randomElement(['Cardiology', 'Neurology', 'Pediatrics', 'Dermatology', 'Orthopedics']),
            'hospital' => $this->faker->company() . ' Hospital',
            'rating' => $this->faker->randomFloat(1, 3, 5),
            'location' => $this->faker->city(),
            'consultation_fee' => $this->faker->numberBetween(50, 300),
        ];
    }
}
