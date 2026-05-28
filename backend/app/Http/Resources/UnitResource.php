<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'beach_club_id' => $this->beach_club_id,
            'sector_id' => $this->sector_id,
            'code' => $this->code,
            'status' => $this->status,
            'x' => $this->x,
            'y' => $this->y,
            'capacity' => $this->capacity,
            'sector' => $this->whenLoaded('sector', fn () => [
                'id' => $this->sector?->id,
                'name' => $this->sector?->name,
                'code' => $this->sector?->code,
            ]),
        ];
    }
}
