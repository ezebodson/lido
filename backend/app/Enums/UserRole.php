<?php

namespace App\Enums;

enum UserRole: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case RECEPTION = 'reception';
    case CASHIER = 'cashier';

    public function label(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => 'Super administrador',
            self::ADMIN => 'Administrador',
            self::RECEPTION => 'Recepción',
            self::CASHIER => 'Caja',
        };
    }
}
