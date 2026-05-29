<?php

namespace App\Helpers;

use App\Enums\UserRole;

final class Tenant
{
    public static function canAccess(?int $resourceBeachClubId): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        if ($user->role === UserRole::SUPER_ADMIN) {
            return true;
        }

        return $resourceBeachClubId === $user->beach_club_id;
    }
}
