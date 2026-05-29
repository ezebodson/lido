<?php

namespace Database\Factories;

use App\Models\BeachClub;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'beach_club_id' => BeachClub::factory(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'document_number' => (string) fake()->unique()->numberBetween(10000000, 49999999),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
