<?php

namespace App\Services;

use App\Enums\UnitStatus;
use App\Models\Reservation;
use App\Models\Unit;
use Illuminate\Validation\ValidationException;

final class ReservationAvailabilityService
{
    public function validate(Unit $unit, string $startDate, string $endDate, ?int $ignoreReservationId = null): void
    {
        if (! $unit->is_active || $unit->status === UnitStatus::MAINTENANCE) {
            throw ValidationException::withMessages([
                'unit_id' => 'La unidad seleccionada no está activa.',
            ]);
        }

        $overlapExists = Reservation::query()
            ->where('unit_id', $unit->id)
            ->when($ignoreReservationId, fn ($query) => $query->whereKeyNot($ignoreReservationId))
            ->whereNotIn('status', ['cancelled'])
            ->where(function ($query) use ($startDate, $endDate): void {
                $query
                    ->whereBetween('start_date', [$startDate, $endDate])
                    ->orWhereBetween('end_date', [$startDate, $endDate])
                    ->orWhere(function ($innerQuery) use ($startDate, $endDate): void {
                        $innerQuery
                            ->whereDate('start_date', '<=', $startDate)
                            ->whereDate('end_date', '>=', $endDate);
                    });
            })
            ->exists();

        if ($overlapExists) {
            throw ValidationException::withMessages([
                'unit_id' => 'La unidad no está disponible para el rango de fechas seleccionado.',
            ]);
        }
    }
}
