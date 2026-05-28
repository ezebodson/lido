<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sector\StoreSectorRequest;
use App\Http\Requests\Sector\UpdateSectorRequest;
use App\Http\Resources\SectorResource;
use App\Models\Sector;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class SectorController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $sectors = $this->tenantQuery(Sector::query(), $request)
            ->withCount('units')
            ->orderBy('position')
            ->paginate(25);

        return SectorResource::collection($sectors);
    }

    public function store(StoreSectorRequest $request): JsonResponse
    {
        $sector = Sector::create($this->tenantPayload($request, $request->validated()));

        return (new SectorResource($sector))->response()->setStatusCode(201);
    }

    public function show(Request $request, Sector $sector): SectorResource
    {
        $this->ensureTenantModel($request, $sector);

        return new SectorResource($sector->loadCount('units'));
    }

    public function update(UpdateSectorRequest $request, Sector $sector): SectorResource
    {
        $this->ensureTenantModel($request, $sector);
        $sector->update($this->tenantPayload($request, $request->validated()));

        return new SectorResource($sector);
    }

    public function destroy(Request $request, Sector $sector): Response
    {
        $this->ensureTenantModel($request, $sector);
        $sector->delete();

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

    private function ensureTenantModel(Request $request, Sector $sector): void
    {
        $user = $request->user();
        abort_unless($user && ($user->isSuperAdmin() || $user->beach_club_id === $sector->beach_club_id), 403);
    }
}
