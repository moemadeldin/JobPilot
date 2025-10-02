<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use App\Utils\APIResponses;
use Closure;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureUserIsOwner
{
    use APIResponses;

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(#[CurrentUser] User $user, Request $request, Closure $next): JsonResponse|Response
    {
        if ($user->isAdmin() || $user->isOwner()) {
            return $next($request);
        }

        return $this->fail('Forbidden', Response::HTTP_FORBIDDEN);
    }
}
