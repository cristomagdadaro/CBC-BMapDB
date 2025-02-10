<?php

namespace App\Http\Middleware;

use App\Enums\Applications;
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
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response | \Inertia\Response
    {
        $temp = Application::where('name', Applications::BREEDERS_MAP->value)->where('status', true)->first();

        if (!$temp) {
            return inertia('Auth/ApplicationDown', [
                'message' => Applications::BREEDERS_MAP->value . ' is currently down. Please try again later.'
            ]);
        }

        return $next($request);
    }
}
