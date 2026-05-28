<?php

namespace App\Policies;

use App\Models\BeachClub;
use App\Models\User;

class BeachClubPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, BeachClub $beachClub): bool
    {
        return $user->isSuperAdmin() || $user->beach_club_id === $beachClub->id;
    }

    public function create(User $user): bool
    {
        return $user->isSuperAdmin();
    }

    public function update(User $user, BeachClub $beachClub): bool
    {
        return $user->isSuperAdmin() || $user->beach_club_id === $beachClub->id;
    }

    public function delete(User $user, BeachClub $beachClub): bool
    {
        return $user->isSuperAdmin();
    }
}
