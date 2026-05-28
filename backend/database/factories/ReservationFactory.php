<?php

namespace Database\Factories;

use App\Models\BeachClub;
use App\Models\Customer;
use App\Models\Reservation;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reservation>
 */
class ReservationFactory extends Factory
{
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-10 days', '+15 days');
        $end = (clone $start)->modify('+'.fake()->numberBetween(1, 5).' days');

        return [
            'beach_club_id' => BeachClub::factory(),
            'unit_id' => Unit::factory(),
            'customer_id' => Customer::factory(),
            'status' => fake()->randomElement(['pending', 'confirmed', 'checked_in']),
            'start_date' => $start,
            'end_date' => $end,
            'guests' => fake()->numberBetween(1, 4),
            'total_amount' => fake()->randomFloat(2, 30, 450),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
