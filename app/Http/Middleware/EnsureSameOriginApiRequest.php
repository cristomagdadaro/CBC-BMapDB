<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureSameOriginApiRequest
{
    public function handle(Request $request, Closure $next)
    {
        if (app()->environment(['local', 'testing'])) {
            return $next($request);
        }

        $allowedOrigins = array_values(array_unique(array_filter([
            ...config('openai.allowed_origins', []),
            rtrim($request->getSchemeAndHttpHost(), '/'),
        ])));

        $origin = $this->normalizeOrigin((string) $request->headers->get('Origin'));
        $referer = $this->extractOriginFromUrl((string) $request->headers->get('Referer'));

        if (
            ($origin !== null && in_array($origin, $allowedOrigins, true))
            || ($referer !== null && in_array($referer, $allowedOrigins, true))
        ) {
            return $next($request);
        }

        abort(403, 'Cross-origin access is not allowed for this endpoint.');
    }

    private function normalizeOrigin(string $origin): ?string
    {
        $normalized = rtrim(trim($origin), '/');

        return $normalized !== '' ? $normalized : null;
    }

    private function extractOriginFromUrl(string $url): ?string
    {
        if (trim($url) === '') {
            return null;
        }

        $parts = parse_url($url);
        if (!is_array($parts) || empty($parts['scheme']) || empty($parts['host'])) {
            return null;
        }

        $port = isset($parts['port']) ? ':' . $parts['port'] : '';

        return sprintf('%s://%s%s', $parts['scheme'], $parts['host'], $port);
    }
}
