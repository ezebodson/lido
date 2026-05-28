<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'beach_club_id' => $this->beach_club_id,
            'name' => $this->name,
            'code' => $this->code,
            'position' => $this->position,
            'units_count' => $this->whenCounted('units'),
        ];
    }
}
