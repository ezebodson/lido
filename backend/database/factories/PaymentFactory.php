<?php

namespace Database\Factories;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\BeachClub;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'beach_club_id' => BeachClub::factory(),
            'reservation_id' => Reservation::factory(),
            'amount' => fake()->randomFloat(2, 10000, 120000),
            'method' => fake()->randomElement(PaymentMethod::cases()),
            'status' => PaymentStatus::PAID,
            'paid_at' => now(),
            'reference' => strtoupper(fake()->bothify('PAY-####')),
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
