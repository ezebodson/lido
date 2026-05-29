<?php

namespace App\Enums;

enum UnitStatus: string
{
    case AVAILABLE = 'available';
    case OCCUPIED = 'occupied';
    case MAINTENANCE = 'maintenance';

    public function label(): string
    {
        return match ($this) {
            self::AVAILABLE => 'Disponible',
            self::OCCUPIED => 'Ocupada',
            self::MAINTENANCE => 'Mantenimiento',
        };
    }
}
