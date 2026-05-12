<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        abort_if(
            ! $user || ! $user->hasAnyRole($roles),
            Response::HTTP_FORBIDDEN,
            'Vous n\'avez pas acces a cette ressource.'
        );

        return $next($request);
    }
}
