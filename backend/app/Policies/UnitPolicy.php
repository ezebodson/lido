<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Helpers\Tenant;
use App\Models\Unit;
use App\Models\User;

class UnitPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::SUPER_ADMIN, UserRole::ADMIN, UserRole::RECEPTION], true);
    }

    public function view(User $user, Unit $unit): bool
    {
        return $this->viewAny($user) && Tenant::canAccess($unit->beach_club_id);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::SUPER_ADMIN, UserRole::ADMIN], true);
    }

    public function update(User $user, Unit $unit): bool
    {
        return $this->create($user) && Tenant::canAccess($unit->beach_club_id);
    }

    public function delete(User $user, Unit $unit): bool
    {
        return $this->create($user) && Tenant::canAccess($unit->beach_club_id);
    }
}
