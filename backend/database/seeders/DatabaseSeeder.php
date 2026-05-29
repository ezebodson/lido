<?php

namespace Database\Seeders;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\ReservationStatus;
use App\Enums\UnitStatus;
use App\Enums\UserRole;
use App\Models\BeachClub;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Rate;
use App\Models\Reservation;
use App\Models\Sector;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $beachClub = BeachClub::query()->create([
            'name' => 'LIDO Demo Beach Club',
            'slug' => 'lido-demo',
            'timezone' => 'America/Argentina/Buenos_Aires',
            'currency' => 'ARS',
            'is_active' => true,
        ]);

        foreach ([
            ['name' => 'Super Admin Demo', 'email' => 'superadmin@lido.test', 'role' => UserRole::SUPER_ADMIN],
            ['name' => 'Admin Demo', 'email' => 'admin@lido.test', 'role' => UserRole::ADMIN],
            ['name' => 'Recepción Demo', 'email' => 'recepcion@lido.test', 'role' => UserRole::RECEPTION],
            ['name' => 'Caja Demo', 'email' => 'caja@lido.test', 'role' => UserRole::CASHIER],
        ] as $index => $userData) {
            User::query()->create([
                'beach_club_id' => $beachClub->id,
                'name' => $userData['name'],
                'email' => $userData['email'],
                'phone' => '+54 11 4000 000'.($index + 1),
                'role' => $userData['role'],
                'is_active' => true,
                'password' => 'password',
            ]);
        }

        $sectors = collect([
            ['name' => 'Premium', 'code' => 'PRM'],
            ['name' => 'Costa Norte', 'code' => 'NOR'],
            ['name' => 'Costa Sur', 'code' => 'SUR'],
            ['name' => 'Familias', 'code' => 'FAM'],
            ['name' => 'Relax', 'code' => 'RLX'],
        ])->map(fn (array $sector) => Sector::query()->create([
            'beach_club_id' => $beachClub->id,
            'name' => $sector['name'],
            'code' => $sector['code'],
            'description' => 'Sector '.$sector['name'],
            'is_active' => true,
        ]));

        $rates = $sectors->map(fn (Sector $sector) => Rate::query()->create([
            'beach_club_id' => $beachClub->id,
            'sector_id' => $sector->id,
            'name' => 'Tarifa '.$sector->name,
            'billing_type' => 'daily',
            'amount' => fake()->randomFloat(2, 35000, 85000),
            'is_active' => true,
        ]));

        $units = collect();
        foreach ($sectors as $sectorIndex => $sector) {
            foreach (range(1, 20) as $position) {
                $units->push(Unit::query()->create([
                    'beach_club_id' => $beachClub->id,
                    'sector_id' => $sector->id,
                    'name' => 'Unidad '.($sectorIndex + 1).'-'.str_pad((string) $position, 2, '0', STR_PAD_LEFT),
                    'code' => $sector->code.'-'.str_pad((string) $position, 3, '0', STR_PAD_LEFT),
                    'capacity' => fake()->numberBetween(2, 6),
                    'status' => UnitStatus::AVAILABLE,
                    'is_active' => true,
                ]));
            }
        }

        $customers = Customer::factory()->count(30)->create([
            'beach_club_id' => $beachClub->id,
        ]);

        foreach ($units->take(40) as $index => $unit) {
            $customer = $customers[$index % $customers->count()];
            $rate = $rates[$index % $rates->count()];
            $startDate = Carbon::today()->addDays($index % 10 - 2);
            $endDate = (clone $startDate)->addDays(($index % 5) + 1);
            $totalAmount = (float) $rate->amount * (($index % 5) + 1);

            $reservation = Reservation::query()->create([
                'beach_club_id' => $beachClub->id,
                'customer_id' => $customer->id,
                'unit_id' => $unit->id,
                'rate_id' => $rate->id,
                'code' => 'RSV-'.Str::upper(Str::random(8)),
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => $startDate->isFuture() ? ReservationStatus::CONFIRMED : ReservationStatus::CHECKED_IN,
                'guests' => min($unit->capacity, fake()->numberBetween(1, 4)),
                'total_amount' => $totalAmount,
                'paid_amount' => 0,
                'notes' => fake()->sentence(),
            ]);

            if ($index % 3 !== 0) {
                Payment::query()->create([
                    'beach_club_id' => $beachClub->id,
                    'reservation_id' => $reservation->id,
                    'amount' => $index % 2 === 0 ? $totalAmount : round($totalAmount * 0.5, 2),
                    'method' => fake()->randomElement([PaymentMethod::CASH, PaymentMethod::CARD, PaymentMethod::TRANSFER]),
                    'status' => PaymentStatus::PAID,
                    'paid_at' => now()->subDays(fake()->numberBetween(0, 5)),
                    'reference' => strtoupper(fake()->bothify('PAY-####')),
                    'notes' => 'Pago demo',
                ]);

                $reservation->syncPaidAmount();
            }
        }
    }
}
