<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Helpers\Tenant;
use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::SUPER_ADMIN, UserRole::ADMIN, UserRole::RECEPTION], true);
    }

    public function view(User $user, Customer $customer): bool
    {
        return $this->viewAny($user) && Tenant::canAccess($customer->beach_club_id);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::SUPER_ADMIN, UserRole::ADMIN, UserRole::RECEPTION], true);
    }

    public function update(User $user, Customer $customer): bool
    {
        return $this->create($user) && Tenant::canAccess($customer->beach_club_id);
    }

    public function delete(User $user, Customer $customer): bool
    {
        return in_array($user->role, [UserRole::SUPER_ADMIN, UserRole::ADMIN], true) && Tenant::canAccess($customer->beach_club_id);
    }
}
