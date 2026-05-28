<?php

namespace Database\Seeders;

use App\Models\BeachClub;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Rate;
use App\Models\Reservation;
use App\Models\Sector;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $club = BeachClub::factory()->create([
            'name' => 'Demo Lido Club',
            'slug' => 'demo-lido-club',
            'address' => '1 Seaside Avenue',
        ]);

        User::factory()->superAdmin()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@demo.test',
        ]);

        User::factory()->create([
            'name' => 'Beach Admin',
            'email' => 'admin@demo.test',
            'role' => 'beach_admin',
            'beach_club_id' => $club->id,
        ]);

        User::factory()->create([
            'name' => 'Reception User',
            'email' => 'reception@demo.test',
            'role' => 'reception',
            'beach_club_id' => $club->id,
        ]);

        User::factory()->create([
            'name' => 'Cashier User',
            'email' => 'cashier@demo.test',
            'role' => 'cashier',
            'beach_club_id' => $club->id,
        ]);

        $sectors = collect(['North', 'Center', 'South', 'VIP', 'Family'])->map(
            fn (string $name, int $index) => Sector::factory()->create([
                'beach_club_id' => $club->id,
                'name' => $name,
                'code' => 'S'.($index + 1),
                'position' => $index + 1,
            ])
        );

        $units = collect();
        for ($i = 1; $i <= 100; $i++) {
            $sector = $sectors[($i - 1) % $sectors->count()];
            $units->push(Unit::factory()->create([
                'beach_club_id' => $club->id,
                'sector_id' => $sector->id,
                'code' => sprintf('U%03d', $i),
                'status' => $i % 13 === 0 ? 'maintenance' : 'available',
            ]));
        }

        $customers = Customer::factory(30)->create(['beach_club_id' => $club->id]);

        Rate::factory()->create([
            'beach_club_id' => $club->id,
            'name' => 'Regular Daily',
            'daily_price' => 45,
            'unit_type' => 'standard',
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->addMonths(3)->endOfMonth(),
        ]);

        Rate::factory()->create([
            'beach_club_id' => $club->id,
            'name' => 'VIP Daily',
            'daily_price' => 80,
            'unit_type' => 'vip',
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->addMonths(3)->endOfMonth(),
        ]);

        $reservations = $this->seedReservations($club->id, $units, $customers);

        foreach ($reservations->take(20) as $reservation) {
            Payment::factory()->create([
                'beach_club_id' => $club->id,
                'reservation_id' => $reservation->id,
                'customer_id' => $reservation->customer_id,
                'amount' => $reservation->total_amount,
                'status' => 'paid',
            ]);
        }
    }

    private function seedReservations(int $clubId, Collection $units, Collection $customers): Collection
    {
        return collect(range(0, 24))->map(function (int $offset) use ($clubId, $units, $customers) {
            $start = now()->addDays($offset - 8)->toDateString();
            $end = now()->addDays($offset - 6)->toDateString();
            $unit = $units->where('status', '!=', 'maintenance')->values()[$offset % 80];
            $customer = $customers[$offset % $customers->count()];

            return Reservation::factory()->create([
                'beach_club_id' => $clubId,
                'unit_id' => $unit->id,
                'customer_id' => $customer->id,
                'start_date' => $start,
                'end_date' => $end,
                'status' => $offset % 7 === 0 ? 'pending' : 'confirmed',
                'total_amount' => fake()->randomFloat(2, 60, 320),
            ]);
        });
    }
}
