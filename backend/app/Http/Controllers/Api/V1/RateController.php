<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rate\StoreRateRequest;
use App\Http\Requests\Rate\UpdateRateRequest;
use App\Http\Resources\RateResource;
use App\Models\Rate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class RateController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $rates = $this->tenantQuery(Rate::query(), $request)
            ->orderByDesc('start_date')
            ->paginate(25);

        return RateResource::collection($rates);
    }

    public function store(StoreRateRequest $request): JsonResponse
    {
        $rate = Rate::create($this->tenantPayload($request, $request->validated()));

        return (new RateResource($rate))->response()->setStatusCode(201);
    }

    public function show(Request $request, Rate $rate): RateResource
    {
        $this->ensureTenantModel($request, $rate);

        return new RateResource($rate);
    }

    public function update(UpdateRateRequest $request, Rate $rate): RateResource
    {
        $this->ensureTenantModel($request, $rate);
        $rate->update($this->tenantPayload($request, $request->validated()));

        return new RateResource($rate);
    }

    public function destroy(Request $request, Rate $rate): Response
    {
        $this->ensureTenantModel($request, $rate);
        $rate->delete();

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

    private function ensureTenantModel(Request $request, Rate $rate): void
    {
        $user = $request->user();
        abort_unless($user && ($user->isSuperAdmin() || $user->beach_club_id === $rate->beach_club_id), 403);
    }
}
