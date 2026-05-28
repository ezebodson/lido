<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CalendarResource;
use App\Models\Reservation;
use App\Models\Unit;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function occupancy(Request $request): CalendarResource
    {
        $start = Carbon::parse($request->input('start_date', now()->startOfMonth()->toDateString()));
        $end = Carbon::parse($request->input('end_date', now()->endOfMonth()->toDateString()));
        $user = $request->user();

        $unitCount = $this->tenantQuery(Unit::query(), $request)->count();
        $reservations = $this->tenantQuery(Reservation::query(), $request)
            ->whereIn('status', ['confirmed', 'checked_in', 'pending'])
            ->whereDate('start_date', '<=', $end)
            ->whereDate('end_date', '>=', $start)
            ->get(['unit_id', 'start_date', 'end_date']);

        $days = [];
        foreach (CarbonPeriod::create($start, $end) as $day) {
            $occupied = $reservations
                ->filter(fn (Reservation $reservation) => Carbon::parse($reservation->start_date)->lte($day)
                    && Carbon::parse($reservation->end_date)->gte($day)
                )
                ->pluck('unit_id')
                ->unique()
                ->count();

            $days[] = [
                'date' => $day->toDateString(),
                'occupied_units' => $occupied,
                'total_units' => $unitCount,
                'occupancy_rate' => $unitCount > 0 ? round(($occupied / $unitCount) * 100, 2) : 0,
            ];
        }

        return new CalendarResource([
            'from' => $start->toDateString(),
            'to' => $end->toDateString(),
            'days' => $days,
        ]);
    }

    private function tenantQuery(Builder $query, Request $request): Builder
    {
        $user = $request->user();

        return $user && ! $user->isSuperAdmin()
            ? $query->where('beach_club_id', $user->beach_club_id)
            : $query;
    }
}
