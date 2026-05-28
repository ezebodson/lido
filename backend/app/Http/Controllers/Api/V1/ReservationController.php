<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Http\Requests\Reservation\UpdateReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ReservationController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $reservations = $this->tenantQuery(Reservation::query(), $request)
            ->with(['unit:id,code,sector_id', 'customer:id,first_name,last_name'])
            ->when($request->filled('status'), fn (Builder $query) => $query->where('status', $request->string('status')))
            ->when($request->filled('from'), fn (Builder $query) => $query->whereDate('start_date', '>=', $request->date('from')))
            ->when($request->filled('to'), fn (Builder $query) => $query->whereDate('end_date', '<=', $request->date('to')))
            ->orderByDesc('start_date')
            ->paginate(25);

        return ReservationResource::collection($reservations);
    }

    public function store(StoreReservationRequest $request): JsonResponse
    {
        $reservation = Reservation::create($this->tenantPayload($request, $request->validated()));

        return (new ReservationResource($reservation->load(['unit', 'customer'])))->response()->setStatusCode(201);
    }

    public function show(Request $request, Reservation $reservation): ReservationResource
    {
        $this->ensureTenantModel($request, $reservation);

        return new ReservationResource($reservation->load(['unit', 'customer', 'payments']));
    }

    public function update(UpdateReservationRequest $request, Reservation $reservation): ReservationResource
    {
        $this->ensureTenantModel($request, $reservation);
        $reservation->update($this->tenantPayload($request, $request->validated()));

        return new ReservationResource($reservation->load(['unit', 'customer']));
    }

    public function destroy(Request $request, Reservation $reservation): Response
    {
        $this->ensureTenantModel($request, $reservation);
        $reservation->delete();

        return response()->noContent();
    }

    private function tenantQuery(Builder $query, Request $request): Builder
    {
        $user = $request->user();

        return $user && ! $user->isSuperAdmin()
            ? $query->where('beach_club_id', $user->beach_club_id)
            : $query;
    }

    private function tenantPayload(Request $request, array $data): array
    {
        $user = $request->user();

        if ($user && ! $user->isSuperAdmin()) {
            $data['beach_club_id'] = $user->beach_club_id;
        }

        return $data;
    }

    private function ensureTenantModel(Request $request, Reservation $reservation): void
    {
        $user = $request->user();
        abort_unless($user && ($user->isSuperAdmin() || $user->beach_club_id === $reservation->beach_club_id), 403);
    }
}
