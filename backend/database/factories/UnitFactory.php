<?php

namespace Database\Factories;

use App\Enums\UnitStatus;
use App\Models\BeachClub;
use App\Models\Sector;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitFactory extends Factory
{
    public function definition(): array
    {
        return [
            'beach_club_id' => BeachClub::factory(),
            'sector_id' => Sector::factory(),
            'name' => 'Unidad '.fake()->unique()->numberBetween(1, 999),
            'code' => strtoupper(fake()->unique()->bothify('U###')),
            'capacity' => fake()->numberBetween(2, 6),
            'status' => UnitStatus::AVAILABLE,
            'is_active' => true,
        ];
    }
}
