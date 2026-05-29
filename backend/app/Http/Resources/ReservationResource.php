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
            'code' => $this->code,
            'customer_id' => $this->customer_id,
            'unit_id' => $this->unit_id,
            'rate_id' => $this->rate_id,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
            'status' => $this->status?->value,
            'status_label' => $this->status?->label(),
            'guests' => $this->guests,
            'total_amount' => (float) $this->total_amount,
            'paid_amount' => (float) $this->paid_amount,
            'pending_amount' => (float) $this->pending_amount,
            'notes' => $this->notes,
            'customer' => $this->whenLoaded('customer', fn () => [
                'id' => $this->customer?->id,
                'full_name' => $this->customer?->full_name,
                'email' => $this->customer?->email,
            ]),
            'unit' => $this->whenLoaded('unit', fn () => [
                'id' => $this->unit?->id,
                'name' => $this->unit?->name,
                'code' => $this->unit?->code,
                'sector' => $this->unit?->sector?->name,
            ]),
            'payment' => PaymentResource::make($this->whenLoaded('payment')),
        ];
    }
}
