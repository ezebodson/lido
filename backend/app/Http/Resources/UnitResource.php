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
            'sector_id' => $this->sector_id,
            'name' => $this->name,
            'code' => $this->code,
            'capacity' => $this->capacity,
            'status' => $this->status?->value,
            'status_label' => $this->status?->label(),
            'is_active' => $this->is_active,
            'sector' => $this->whenLoaded('sector', fn () => [
                'id' => $this->sector?->id,
                'name' => $this->sector?->name,
                'code' => $this->sector?->code,
            ]),
        ];
    }
}
