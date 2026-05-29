<?php

namespace App\Repositories;

use App\Models\Reservation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class ReservationRepository
{
    public function paginate(array $filters): LengthAwarePaginator
    {
        return Reservation::query()
            ->with(['customer', 'unit.sector', 'payment'])
            ->when($filters['search'] ?? null, function ($query, $search): void {
                $query->where(function ($innerQuery) use ($search): void {
                    $innerQuery
                        ->where('code', 'like', "%{$search}%")
                        ->orWhereHas('customer', fn ($customerQuery) => $customerQuery
                            ->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                        )
                        ->orWhereHas('unit', fn ($unitQuery) => $unitQuery
                            ->where('name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%")
                        );
                });
            })
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->when($filters['start_date'] ?? null, fn ($query, $date) => $query->whereDate('start_date', '>=', $date))
            ->when($filters['end_date'] ?? null, fn ($query, $date) => $query->whereDate('end_date', '<=', $date))
            ->latest('start_date')
            ->paginate((int) ($filters['per_page'] ?? 15))
            ->withQueryString();
    }
}
