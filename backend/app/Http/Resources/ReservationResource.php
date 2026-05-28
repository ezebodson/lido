<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'beach_club_id' => $this->beach_club_id,
            'unit_id' => $this->unit_id,
            'customer_id' => $this->customer_id,
            'status' => $this->status,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
            'guests' => $this->guests,
            'total_amount' => (float) $this->total_amount,
            'notes' => $this->notes,
            'unit' => $this->whenLoaded('unit', fn () => [
                'id' => $this->unit?->id,
                'code' => $this->unit?->code,
            ]),
            'customer' => $this->whenLoaded('customer', fn () => [
                'id' => $this->customer?->id,
                'name' => trim(($this->customer?->first_name ?? '').' '.($this->customer?->last_name ?? '')),
            ]),
        ];
    }
}
