<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;

class ReservationPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Reservation $reservation): bool
    {
        return $user->isSuperAdmin() || $user->beach_club_id === $reservation->beach_club_id;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'beach_admin', 'reception'], true);
    }

    public function update(User $user, Reservation $reservation): bool
    {
        return $this->create($user) && $this->view($user, $reservation);
    }

    public function delete(User $user, Reservation $reservation): bool
    {
        return in_array($user->role, ['super_admin', 'beach_admin'], true)
            && ($user->isSuperAdmin() || $user->beach_club_id === $reservation->beach_club_id);
    }
}
