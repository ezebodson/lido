<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\UpsertReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use App\Models\Unit;
use App\Repositories\ReservationRepository;
use App\Services\ReservationAvailabilityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReservationController extends Controller
{
    public function __construct(
        private readonly ReservationRepository $reservationRepository,
        private readonly ReservationAvailabilityService $availabilityService,
    ) {
        $this->authorizeResource(Reservation::class, 'reservation');
    }

    public function index(Request $request)
    {
        return ReservationResource::collection($this->reservationRepository->paginate($request->all()));
    }

    public function store(UpsertReservationRequest $request): ReservationResource
    {
        $data = $request->validated();
        $unit = Unit::query()->findOrFail($data['unit_id']);

        $this->availabilityService->validate($unit, $data['start_date'], $data['end_date']);

        $reservation = Reservation::query()->create([
            ...$data,
            'beach_club_id' => $request->user()->beach_club_id,
            'code' => 'RSV-'.strtoupper(Str::random(8)),
            'paid_amount' => 0,
        ]);

        return ReservationResource::make($reservation->load(['customer', 'unit.sector', 'payment']));
    }

    public function show(Reservation $reservation): ReservationResource
    {
        return ReservationResource::make($reservation->load(['customer', 'unit.sector', 'payment']));
    }

    public function update(UpsertReservationRequest $request, Reservation $reservation): ReservationResource
    {
        $data = $request->validated();
        $unit = Unit::query()->findOrFail($data['unit_id']);

        $this->availabilityService->validate($unit, $data['start_date'], $data['end_date'], $reservation->id);

        $reservation->update($data);
        $reservation->refresh()->syncPaidAmount();

        return ReservationResource::make($reservation->load(['customer', 'unit.sector', 'payment']));
    }

    public function destroy(Reservation $reservation): JsonResponse
    {
        $reservation->delete();

        return response()->json([
            'message' => 'Reserva eliminada correctamente.',
        ]);
    }
}
