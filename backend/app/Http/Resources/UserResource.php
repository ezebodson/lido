<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role?->value,
            'role_label' => $this->role?->label(),
            'phone' => $this->phone,
            'is_active' => $this->is_active,
            'beach_club' => $this->whenLoaded('beachClub', fn () => [
                'id' => $this->beachClub?->id,
                'name' => $this->beachClub?->name,
                'slug' => $this->beachClub?->slug,
            ]),
        ];
    }
}
