<?php

namespace App\Http\Middleware;

use App\Models\Application;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTwgDbStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response | \Inertia\Response
    {
        // Check if the TWG database is up, check if the status is true
        $temp = Application::where('name', 'TWG Database')->where('status', true)->first();

        if (!$temp) {
            return inertia('Auth/ApplicationDown', [
                'message' => 'TWG Database is currently down. Please try again later.'
            ], 503);
        }

        return $next($request);
    }
}
