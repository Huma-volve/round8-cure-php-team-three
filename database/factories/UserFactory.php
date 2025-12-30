<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $images = [
            'https://i.pinimg.com/736x/6c/6e/d7/6c6ed7f4011b7f926b3f1505475aba16.jpg',
            'https://i.pinimg.com/736x/2b/67/ea/2b67eab2f47654a38614999133170c4c.jpg',
            'https://i.pinimg.com/1200x/9c/18/40/9c1840309da5d95319124fae7975bbaf.jpg',
            'https://i.pinimg.com/1200x/94/6e/cf/946ecf2091b6057cb2179e0d9c000f2d.jpg',
            'https://i.pinimg.com/1200x/3d/30/c4/3d30c4a1fa780e4dc221ed7ef64298a8.jpg',
            'https://i.pinimg.com/1200x/0f/02/a7/0f02a79e8aa192e7e2a668bad9ad6505.jpg',
            'https://i.pinimg.com/736x/8d/1c/af/8d1caf76c2f1b07b3e68cf7144a80e19.jpg',
            'https://i.pinimg.com/1200x/bd/a5/bf/bda5bf9b4a8ad6ac1d723fa2733047c5.jpg',
            'https://i.pinimg.com/1200x/b0/ee/66/b0ee6603721013bbbf66a76b85488c5b.jpg',
            'https://i.pinimg.com/736x/f2/c7/c7/f2c7c7c8b52129111b78730066e646ec.jpg',
            'https://i.pinimg.com/1200x/d0/75/7d/d0757db20f67bf64075c47c6b600d6ad.jpg',
            'https://i.pinimg.com/1200x/7d/6c/37/7d6c37451113490accea2dca24dc442d.jpg',
            'https://i.pinimg.com/1200x/00/f0/7a/00f07a1939f2834843c8ce39c7e672c6.jpg',
            'https://i.pinimg.com/736x/55/7c/4e/557c4eaa60fe2f5665b4a80a789fed22.jpg',
            'https://i.pinimg.com/1200x/ce/fc/22/cefc22e761eaf9a255beac13f17a5c0b.jpg',
            'https://i.pinimg.com/736x/ea/4b/29/ea4b294b692228e74c8fa774181c9dd3.jpg',
            'https://i.pinimg.com/1200x/09/b7/84/09b784f0da470f9593a1654f808036b6.jpg',
            'https://i.pinimg.com/1200x/23/6b/ba/236bbabd45db93006448069f3cc65fea.jpg',
            'https://i.pinimg.com/736x/a6/4c/61/a64c6182aed5ff10f869ff8d220f7ccf.jpg',
            'https://i.pinimg.com/736x/2a/d0/d5/2ad0d59c4bd62f5c8a93879ac39566ef.jpg',
            'https://i.pinimg.com/1200x/43/55/88/4355881cc128640e635945a049700732.jpg',
            'https://i.pinimg.com/736x/3c/6d/50/3c6d50f88ebae561bdc5ec88ee9caa2a.jpg',
        ];

        return [
            'name' => 'Dr. ' . $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'mobile_number' => $this->faker->unique()->numerify('01#########'),
            'profile_photo' => $this->faker->randomElement($images),
            'remember_token' => Str::random(10),

        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
