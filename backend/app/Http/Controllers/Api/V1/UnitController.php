<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Unit\StoreUnitRequest;
use App\Http\Requests\Unit\UpdateUnitRequest;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class UnitController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $units = $this->tenantQuery(Unit::query(), $request)
            ->with(['sector'])
            ->when($request->filled('status'), fn (Builder $query) => $query->where('status', $request->string('status')))
            ->orderBy('code')
            ->paginate(30);

        return UnitResource::collection($units);
    }

    public function map(Request $request): JsonResponse
    {
        $units = $this->tenantQuery(Unit::query(), $request)
            ->with(['sector:id,name,code'])
            ->orderBy('sector_id')
            ->orderBy('code')
            ->get();

        $payload = $units->groupBy(fn (Unit $unit) => $unit->sector?->name ?? 'Unassigned')
            ->map(fn ($group) => UnitResource::collection($group)->resolve())
            ->toArray();

        return response()->json(['data' => $payload]);
    }

    public function store(StoreUnitRequest $request): JsonResponse
    {
        $unit = Unit::create($this->tenantPayload($request, $request->validated()));

        return (new UnitResource($unit->load('sector')))->response()->setStatusCode(201);
    }

    public function show(Request $request, Unit $unit): UnitResource
    {
        $this->ensureTenantModel($request, $unit);

        return new UnitResource($unit->load('sector'));
    }

    public function update(UpdateUnitRequest $request, Unit $unit): UnitResource
    {
        $this->ensureTenantModel($request, $unit);
        $unit->update($this->tenantPayload($request, $request->validated()));

        return new UnitResource($unit->load('sector'));
    }

    public function destroy(Request $request, Unit $unit): Response
    {
        $this->ensureTenantModel($request, $unit);
        $unit->delete();

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

    private function ensureTenantModel(Request $request, Unit $unit): void
    {
        $user = $request->user();
        abort_unless($user && ($user->isSuperAdmin() || $user->beach_club_id === $unit->beach_club_id), 403);
    }
}
