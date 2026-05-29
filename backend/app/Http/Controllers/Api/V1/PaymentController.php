<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\UpsertPaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Payment::class, 'payment');
    }

    public function index(Request $request)
    {
        $payments = Payment::query()
            ->with('reservation')
            ->when($request->string('search')->toString(), function ($query, $search): void {
                $query->where('reference', 'like', "%{$search}%")
                    ->orWhereHas('reservation', fn ($reservationQuery) => $reservationQuery->where('code', 'like', "%{$search}%"));
            })
            ->latest('paid_at')
            ->paginate((int) $request->integer('per_page', 15))
            ->withQueryString();

        return PaymentResource::collection($payments);
    }

    public function store(UpsertPaymentRequest $request): PaymentResource
    {
        $reservation = Reservation::query()->findOrFail($request->integer('reservation_id'));
        $this->ensureReservationIsAccessible($reservation);

        $payment = Payment::query()->create([
            ...$request->validated(),
            'beach_club_id' => $request->user()->beach_club_id,
        ]);

        $payment->reservation->syncPaidAmount();

        return PaymentResource::make($payment->load('reservation'));
    }

    public function show(Payment $payment): PaymentResource
    {
        return PaymentResource::make($payment->load('reservation'));
    }

    public function update(UpsertPaymentRequest $request, Payment $payment): PaymentResource
    {
        $reservation = Reservation::query()->findOrFail($request->integer('reservation_id'));
        $this->ensureReservationIsAccessible($reservation);

        $payment->update($request->validated());
        $payment->reservation->syncPaidAmount();

        return PaymentResource::make($payment->refresh()->load('reservation'));
    }

    public function destroy(Payment $payment): JsonResponse
    {
        $reservation = $payment->reservation;
        $payment->delete();
        $reservation?->syncPaidAmount();

        return response()->json([
            'message' => 'Pago eliminado correctamente.',
        ]);
    }

    private function ensureReservationIsAccessible(Reservation $reservation): void
    {
        if ($reservation->beach_club_id !== request()->user()->beach_club_id && request()->user()->role->value !== 'super_admin') {
            throw ValidationException::withMessages([
                'reservation_id' => 'La reserva seleccionada no existe en el balneario actual.',
            ]);
        }
    }
}
