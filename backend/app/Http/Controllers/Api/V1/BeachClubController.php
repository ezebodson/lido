<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\BeachClub\StoreBeachClubRequest;
use App\Http\Requests\BeachClub\UpdateBeachClubRequest;
use App\Http\Resources\BeachClubResource;
use App\Models\BeachClub;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class BeachClubController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $query = BeachClub::query()->orderBy('name');
        $user = request()->user();

        if ($user && ! $user->isSuperAdmin()) {
            $query->where('id', $user->beach_club_id);
        }

        $clubs = $query->paginate(15);

        return BeachClubResource::collection($clubs);
    }

    public function store(StoreBeachClubRequest $request): JsonResponse
    {
        $this->authorize('create', BeachClub::class);
        $club = BeachClub::create($request->validated());

        return (new BeachClubResource($club))->response()->setStatusCode(201);
    }

    public function show(BeachClub $beachClub): BeachClubResource
    {
        $this->authorize('view', $beachClub);

        return new BeachClubResource($beachClub);
    }

    public function update(UpdateBeachClubRequest $request, BeachClub $beachClub): BeachClubResource
    {
        $this->authorize('update', $beachClub);
        $beachClub->update($request->validated());

        return new BeachClubResource($beachClub);
    }

    public function destroy(BeachClub $beachClub): Response
    {
        $this->authorize('delete', $beachClub);
        $beachClub->delete();

        return response()->noContent();
    }
}
