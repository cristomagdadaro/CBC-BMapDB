<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;

class CheckPreLaunch
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): \Inertia\Response | Response
    {
        if (Cache::get('app_pre_launch', false) && !$request->user()) {
            return Inertia::render('Maintenance/AppNotLaunched');
        }

        return $next($request);
    }
}
