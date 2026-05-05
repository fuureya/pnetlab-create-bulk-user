<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PnetlabUserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'username' => $this->faker->unique()->userName(),
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'pod' => $this->faker->numberBetween(1, 100),
            'role' => $this->faker->randomElement([0, 1]),
            'expired_time' => $this->faker->dateTimeBetween('now', '+1 year'),
        ];
    }
}
