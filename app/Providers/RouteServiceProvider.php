<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('openai-chat', function (Request $request) {
            $requestKey = 'openai-chat:' . ($request->user()?->id ?: $request->ip());
            $perMinute = max((int) config('openai.rate_limit_per_minute', 10), 1);
            $perHour = max((int) config('openai.rate_limit_per_hour', 100), 1);

            return [
                Limit::perMinute($perMinute)->by($requestKey)->response(function () {
                    return response()->json([
                        'success' => false,
                        'message' => 'Too many AI chat requests. Please wait a moment and try again.',
                    ], 429);
                }),
                Limit::perHour($perHour)->by($requestKey . ':hour')->response(function () {
                    return response()->json([
                        'success' => false,
                        'message' => 'AI chat rate limit reached for this hour. Please try again later.',
                    ], 429);
                }),
            ];
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
