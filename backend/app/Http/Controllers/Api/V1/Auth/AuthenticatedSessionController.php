<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Actions\Auth\CreateTokenAction;
use App\DTOs\LoginData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    public function store(LoginRequest $request, CreateTokenAction $createTokenAction): JsonResponse
    {
        $data = LoginData::fromArray($request->validated());

        $user = User::query()->with('beachClub')->where('email', $data->email)->first();

        if (! $user || ! Hash::check($data->password, $user->password) || ! $user->is_active) {
            throw ValidationException::withMessages([
                'email' => 'Las credenciales proporcionadas no son válidas.',
            ]);
        }

        $user->tokens()->delete();
        $token = $createTokenAction->execute($user, $data->deviceName ?? 'panel-web');

        return response()->json([
            'message' => 'Inicio de sesión correcto.',
            'token' => $token,
            'user' => UserResource::make($user),
        ]);
    }

    public function destroy(): JsonResponse
    {
        request()->user()?->currentAccessToken()?->delete();

        return response()->json([
            'message' => 'Sesión cerrada correctamente.',
        ]);
    }

    public function me(): UserResource
    {
        return UserResource::make(request()->user()->load('beachClub'));
    }
}
