<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Requests\Payment\UpdatePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class PaymentController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $payments = $this->tenantQuery(Payment::query(), $request)
            ->with(['reservation:id,start_date,end_date,unit_id', 'customer:id,first_name,last_name'])
            ->when($request->filled('status'), fn (Builder $query) => $query->where('status', $request->string('status')))
            ->orderByDesc('paid_at')
            ->paginate(25);

        return PaymentResource::collection($payments);
    }

    public function store(StorePaymentRequest $request): JsonResponse
    {
        $payment = Payment::create($this->tenantPayload($request, $request->validated()));

        return (new PaymentResource($payment->load(['reservation', 'customer'])))->response()->setStatusCode(201);
    }

    public function show(Request $request, Payment $payment): PaymentResource
    {
        $this->ensureTenantModel($request, $payment);

        return new PaymentResource($payment->load(['reservation', 'customer']));
    }

    public function update(UpdatePaymentRequest $request, Payment $payment): PaymentResource
    {
        $this->ensureTenantModel($request, $payment);
        $payment->update($this->tenantPayload($request, $request->validated()));

        return new PaymentResource($payment->load(['reservation', 'customer']));
    }

    public function destroy(Request $request, Payment $payment): Response
    {
        $this->ensureTenantModel($request, $payment);
        $payment->delete();

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

    private function ensureTenantModel(Request $request, Payment $payment): void
    {
        $user = $request->user();
        abort_unless($user && ($user->isSuperAdmin() || $user->beach_club_id === $payment->beach_club_id), 403);
    }
}
