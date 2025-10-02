<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Traits\APIResponses;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

final class EnsureUserIsOwner
{
    use APIResponses;

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): JsonResponse|Response
    {
        if (Auth::user()->isAdmin() || Auth::user()->isOwner()) {
            return $next($request);
        }

        return $this->fail('Forbidden', Response::HTTP_FORBIDDEN);
    }
}
