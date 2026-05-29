<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Helpers\Tenant;
use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, [UserRole::SUPER_ADMIN, UserRole::ADMIN, UserRole::CASHIER], true);
    }

    public function view(User $user, Payment $payment): bool
    {
        return $this->viewAny($user) && Tenant::canAccess($payment->beach_club_id);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::SUPER_ADMIN, UserRole::ADMIN, UserRole::CASHIER], true);
    }

    public function update(User $user, Payment $payment): bool
    {
        return $this->create($user) && Tenant::canAccess($payment->beach_club_id);
    }

    public function delete(User $user, Payment $payment): bool
    {
        return in_array($user->role, [UserRole::SUPER_ADMIN, UserRole::ADMIN], true) && Tenant::canAccess($payment->beach_club_id);
    }
}
