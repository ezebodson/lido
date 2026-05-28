<?php

namespace Database\Factories;

use App\Models\BeachClub;
use App\Models\Sector;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Unit>
 */
class UnitFactory extends Factory
{
    public function definition(): array
    {
        return [
            'beach_club_id' => BeachClub::factory(),
            'sector_id' => Sector::factory(),
            'code' => strtoupper(fake()->bothify('U###')),
            'status' => fake()->randomElement(['available', 'occupied', 'reserved']),
            'x' => fake()->randomFloat(2, 0, 100),
            'y' => fake()->randomFloat(2, 0, 100),
            'capacity' => fake()->numberBetween(2, 6),
        ];
    }
}
