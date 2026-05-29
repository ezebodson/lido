<?php

namespace App\Enums;

enum ReservationStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CHECKED_IN = 'checked_in';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pendiente',
            self::CONFIRMED => 'Confirmada',
            self::CHECKED_IN => 'En curso',
            self::COMPLETED => 'Completada',
            self::CANCELLED => 'Cancelada',
        };
    }
}
