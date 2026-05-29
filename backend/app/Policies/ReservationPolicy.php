<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Helpers\Tenant;
use App\Models\Reservation;
use App\Models\User;

class ReservationPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::SUPER_ADMIN, UserRole::ADMIN, UserRole::RECEPTION, UserRole::CASHIER], true);
    }

    public function view(User $user, Reservation $reservation): bool
    {
        return $this->viewAny($user) && Tenant::canAccess($reservation->beach_club_id);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::SUPER_ADMIN, UserRole::ADMIN, UserRole::RECEPTION], true);
    }

    public function update(User $user, Reservation $reservation): bool
    {
        return $this->create($user) && Tenant::canAccess($reservation->beach_club_id);
    }

    public function delete(User $user, Reservation $reservation): bool
    {
        return in_array($user->role, [UserRole::SUPER_ADMIN, UserRole::ADMIN], true) && Tenant::canAccess($reservation->beach_club_id);
    }
}
