<?php

namespace Database\Factories;

use App\Enums\ReservationStatus;
use App\Models\BeachClub;
use App\Models\Customer;
use App\Models\Rate;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ReservationFactory extends Factory
{
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-5 days', '+15 days');
        $endDate = (clone $startDate)->modify('+'.fake()->numberBetween(1, 7).' days');
        $totalAmount = fake()->randomFloat(2, 50000, 250000);

        return [
            'beach_club_id' => BeachClub::factory(),
            'customer_id' => Customer::factory(),
            'unit_id' => Unit::factory(),
            'rate_id' => Rate::factory(),
            'code' => 'RSV-'.Str::upper(Str::random(8)),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => fake()->randomElement([ReservationStatus::PENDING, ReservationStatus::CONFIRMED, ReservationStatus::CHECKED_IN]),
            'guests' => fake()->numberBetween(1, 6),
            'total_amount' => $totalAmount,
            'paid_amount' => 0,
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
