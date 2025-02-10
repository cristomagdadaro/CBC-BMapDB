<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResearchAccess
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && ($request->user()->isResearcher() || $request->user()->isAdmin())) {
            return $next($request);
        }
        return response()->json(['message' => 'You are not authorized to access. Needs a researcher access.'], 403);
    }
}
