<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->isSuperAdmin()) {
            return $next($request);
        }

        foreach ($request->route()?->parameters() ?? [] as $param) {
            if (! is_object($param) || ! isset($param->beach_club_id)) {
                continue;
            }

            if ((int) $param->beach_club_id !== (int) $user->beach_club_id) {
                return new JsonResponse(['message' => 'Tenant access denied'], Response::HTTP_FORBIDDEN);
            }
        }

        $targetBeachClubId = $request->input('beach_club_id');
        if ($targetBeachClubId !== null && (int) $targetBeachClubId !== (int) $user->beach_club_id) {
            return new JsonResponse(['message' => 'Tenant access denied'], Response::HTTP_FORBIDDEN);
        }

        if ($request->hasHeader('X-Beach-Club-Id')) {
            $headerClubId = (int) $request->header('X-Beach-Club-Id');
            if ($headerClubId !== (int) $user->beach_club_id) {
                return new JsonResponse(['message' => 'Tenant access denied'], Response::HTTP_FORBIDDEN);
            }
        }

        return $next($request);
    }
}
