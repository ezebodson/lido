<?php

namespace Database\Factories;

use App\Models\BeachClub;
use Illuminate\Database\Eloquent\Factories\Factory;

class SectorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'beach_club_id' => BeachClub::factory(),
            'name' => 'Sector '.fake()->unique()->randomLetter(),
            'code' => strtoupper(fake()->unique()->bothify('S##')),
            'description' => fake()->optional()->sentence(),
            'is_active' => true,
        ];
    }
}
