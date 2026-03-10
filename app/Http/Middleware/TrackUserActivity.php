<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrackUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            // Update only if last activity was more than 1 minute ago to reduce DB writes
            if (!$user->last_activity_at || $user->last_activity_at->lt(now()->subMinute())) {
                $user->update(['last_activity_at' => now()]);
            }
        }

        return $next($request);
    }
}

