<?php

namespace Tests\Feature;

use App\Models\BeachClub;
use App\Models\Customer;
use App\Models\Reservation;
use App\Models\Sector;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReservationOverlapValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_rejects_overlapping_reservation_for_same_unit(): void
    {
        [$user, $customer, $unit] = $this->seedTenant();

        Reservation::factory()->create([
            'beach_club_id' => $user->beach_club_id,
            'unit_id' => $unit->id,
            'customer_id' => $customer->id,
            'status' => 'confirmed',
            'start_date' => '2026-07-10',
            'end_date' => '2026-07-15',
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/v1/reservations', [
            'unit_id' => $unit->id,
            'customer_id' => $customer->id,
            'status' => 'confirmed',
            'start_date' => '2026-07-14',
            'end_date' => '2026-07-18',
            'guests' => 2,
            'total_amount' => 120,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['start_date']);
    }

    public function test_it_allows_non_overlapping_and_rejects_maintenance_unit(): void
    {
        [$user, $customer, $unit] = $this->seedTenant();

        Reservation::factory()->create([
            'beach_club_id' => $user->beach_club_id,
            'unit_id' => $unit->id,
            'customer_id' => $customer->id,
            'status' => 'confirmed',
            'start_date' => '2026-07-10',
            'end_date' => '2026-07-12',
        ]);

        Sanctum::actingAs($user);

        $validResponse = $this->postJson('/api/v1/reservations', [
            'unit_id' => $unit->id,
            'customer_id' => $customer->id,
            'status' => 'confirmed',
            'start_date' => '2026-07-13',
            'end_date' => '2026-07-15',
            'guests' => 2,
            'total_amount' => 120,
        ]);

        $validResponse->assertCreated();

        $unit->update(['status' => 'maintenance']);

        $maintenanceResponse = $this->postJson('/api/v1/reservations', [
            'unit_id' => $unit->id,
            'customer_id' => $customer->id,
            'status' => 'confirmed',
            'start_date' => '2026-07-20',
            'end_date' => '2026-07-22',
            'guests' => 2,
            'total_amount' => 120,
        ]);

        $maintenanceResponse->assertStatus(422)
            ->assertJsonValidationErrors(['unit_id']);
    }

    private function seedTenant(): array
    {
        $club = BeachClub::factory()->create();
        $sector = Sector::factory()->create(['beach_club_id' => $club->id]);
        $unit = Unit::factory()->create([
            'beach_club_id' => $club->id,
            'sector_id' => $sector->id,
            'status' => 'available',
        ]);
        $customer = Customer::factory()->create(['beach_club_id' => $club->id]);
        $user = User::factory()->create([
            'beach_club_id' => $club->id,
            'role' => 'beach_admin',
        ]);

        return [$user, $customer, $unit];
    }
}
