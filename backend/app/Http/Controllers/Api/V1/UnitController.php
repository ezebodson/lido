<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Unit\UpsertUnitRequest;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Unit::class, 'unit');
    }

    public function index(Request $request)
    {
        $units = Unit::query()
            ->with('sector')
            ->when($request->string('search')->toString(), function ($query, $search): void {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate((int) $request->integer('per_page', 15))
            ->withQueryString();

        return UnitResource::collection($units);
    }

    public function store(UpsertUnitRequest $request): UnitResource
    {
        $unit = Unit::query()->create([
            ...$request->validated(),
            'beach_club_id' => $request->user()->beach_club_id,
        ]);

        return UnitResource::make($unit->load('sector'));
    }

    public function show(Unit $unit): UnitResource
    {
        return UnitResource::make($unit->load('sector'));
    }

    public function update(UpsertUnitRequest $request, Unit $unit): UnitResource
    {
        $unit->update($request->validated());

        return UnitResource::make($unit->refresh()->load('sector'));
    }

    public function destroy(Unit $unit): JsonResponse
    {
        $unit->delete();

        return response()->json([
            'message' => 'Unidad eliminada correctamente.',
        ]);
    }
}
