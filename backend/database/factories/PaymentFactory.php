<?php

namespace Database\Factories;

use App\Models\BeachClub;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'beach_club_id' => BeachClub::factory(),
            'reservation_id' => Reservation::factory(),
            'customer_id' => Customer::factory(),
            'amount' => fake()->randomFloat(2, 20, 500),
            'method' => fake()->randomElement(['cash', 'card', 'bank_transfer']),
            'status' => fake()->randomElement(['paid', 'pending']),
            'paid_at' => fake()->dateTimeBetween('-30 days', 'now'),
            'reference' => strtoupper(fake()->bothify('PMT-####')),
        ];
    }
}
