<?php

namespace Database\Factories;

use App\Models\BeachClub;
use App\Models\Sector;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sector>
 */
class SectorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'beach_club_id' => BeachClub::factory(),
            'name' => fake()->randomElement(['Sunset', 'Coral', 'Breeze', 'Wave']).' Sector',
            'code' => strtoupper(fake()->bothify('S##')),
            'position' => fake()->numberBetween(1, 20),
        ];
    }
}
