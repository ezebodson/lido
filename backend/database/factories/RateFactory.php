<?php

namespace Database\Factories;

use App\Models\BeachClub;
use App\Models\Rate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Rate>
 */
class RateFactory extends Factory
{
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-2 months', 'now');
        $end = (clone $start)->modify('+'.fake()->numberBetween(15, 60).' days');

        return [
            'beach_club_id' => BeachClub::factory(),
            'name' => fake()->randomElement(['High Season', 'Mid Season', 'Weekend Promo']),
            'unit_type' => fake()->randomElement(['standard', 'vip', 'family']),
            'daily_price' => fake()->randomFloat(2, 20, 200),
            'start_date' => $start,
            'end_date' => $end,
            'is_active' => true,
        ];
    }
}
