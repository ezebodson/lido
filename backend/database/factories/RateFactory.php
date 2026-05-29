<?php

namespace Database\Factories;

use App\Models\BeachClub;
use App\Models\Sector;
use Illuminate\Database\Eloquent\Factories\Factory;

class RateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'beach_club_id' => BeachClub::factory(),
            'sector_id' => Sector::factory(),
            'name' => 'Tarifa '.fake()->unique()->word(),
            'billing_type' => 'daily',
            'amount' => fake()->randomFloat(2, 25000, 120000),
            'is_active' => true,
        ];
    }
}
