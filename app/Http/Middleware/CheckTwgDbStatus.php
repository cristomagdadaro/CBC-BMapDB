<?php

namespace App\Http\Middleware;

use App\Enums\Applications;
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
        $temp = Application::where('name', Applications::TWG_DATABASE->value)->where('status', true)->first();

        if (!$temp) {
            return inertia('Auth/ApplicationDown', [
                'message' => Applications::TWG_DATABASE->value . ' is currently down. Please try again later.'
            ]);
        }

        return $next($request);
    }
}
