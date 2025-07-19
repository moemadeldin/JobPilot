<?php

namespace App\Http\Middleware;

use App\Enums\Roles;
use App\Utils\APIResponses;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    use APIResponses;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): JsonResponse|Response
    {
        if(auth()->user()->hasRole(Roles::ADMIN)) {
            return $next($request);
        }
        return $this->fail('Forbidden', Response::HTTP_FORBIDDEN);
    }
}
