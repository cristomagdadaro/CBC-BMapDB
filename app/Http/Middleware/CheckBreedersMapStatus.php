<?php

namespace App\Http\Middleware;

use App\Models\Application;
use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class CheckBreedersMapStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response | \Inertia\Response
    {
        $temp = Application::where('name', 'Breeder\'s Map')->where('status', true)->first();

        if (!$temp) {
            return inertia('Auth/ApplicationDown', [
                'message' => 'Breeder\'s Map is currently down. Please try again later.'
            ]);
        }

        return $next($request);
    }
}
