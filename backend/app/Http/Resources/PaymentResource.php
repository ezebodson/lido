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
            'beach_club_id' => $this->beach_club_id,
            'reservation_id' => $this->reservation_id,
            'customer_id' => $this->customer_id,
            'amount' => (float) $this->amount,
            'method' => $this->method,
            'status' => $this->status,
            'paid_at' => $this->paid_at,
            'reference' => $this->reference,
            'customer' => $this->whenLoaded('customer', fn () => [
                'id' => $this->customer?->id,
                'name' => trim(($this->customer?->first_name ?? '').' '.($this->customer?->last_name ?? '')),
            ]),
        ];
    }
}
