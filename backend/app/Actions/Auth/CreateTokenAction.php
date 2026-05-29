<?php

namespace App\Actions\Auth;

use App\Models\User;

final class CreateTokenAction
{
    public function execute(User $user, string $deviceName): string
    {
        return $user->createToken($deviceName)->plainTextToken;
    }
}
