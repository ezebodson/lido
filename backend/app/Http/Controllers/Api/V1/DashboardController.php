<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function metrics(Request $request): JsonResponse
    {
        $today = now()->toDateString();

        $units = $this->tenantQuery(Unit::query(), $request)->count();
        $activeReservations = $this->tenantQuery(Reservation::query(), $request)
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->count();

        $metrics = [
            'units' => $units,
            'customers' => $this->tenantQuery(Customer::query(), $request)->count(),
            'active_reservations' => $activeReservations,
            'occupancy_rate' => $units > 0 ? round(($activeReservations / $units) * 100, 2) : 0,
            'today_payments' => (float) $this->tenantQuery(Payment::query(), $request)
                ->whereDate('paid_at', $today)
                ->sum('amount'),
        ];

        return response()->json(['data' => $metrics]);
    }

    private function tenantQuery(Builder $query, Request $request): Builder
    {
        $user = $request->user();

        return $user && ! $user->isSuperAdmin()
            ? $query->where('beach_club_id', $user->beach_club_id)
            : $query;
    }
}
