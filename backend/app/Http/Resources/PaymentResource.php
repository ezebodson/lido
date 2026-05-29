<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reservation_id' => $this->reservation_id,
            'amount' => (float) $this->amount,
            'method' => $this->method?->value,
            'method_label' => $this->method?->label(),
            'status' => $this->status?->value,
            'status_label' => $this->status?->label(),
            'paid_at' => $this->paid_at?->toISOString(),
            'reference' => $this->reference,
            'notes' => $this->notes,
            'reservation' => $this->whenLoaded('reservation', fn () => [
                'id' => $this->reservation?->id,
                'code' => $this->reservation?->code,
            ]),
        ];
    }
}
