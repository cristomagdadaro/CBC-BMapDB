<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ApiRequestLog;

class LogApiRequests
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $userId = Auth::id();
        $userRole = Auth::user() ? Auth::user()->getRole() : null;
        $ip_address = $request->ip();
        $method = $request->method();
        $url = $request->fullUrl();
        $model = $this->getModelFromUrl($url);
        $data = $request->all();
        $modifiedId = $data['id'] ?? null;

        $log = new ApiRequestLog();
        $log->user_id = $userId;
        $log->user_role = $userRole;
        $log->ip_address = $ip_address;
        $log->method = $method;
        $log->url = $url;
        $log->model = $model;
        $log->data = json_encode($data);
        $log->modified_id = $modifiedId;
        $log->save();

        return $response;
    }

    /**
     * Extract model name from the request URL.
     */
    protected function getModelFromUrl(string $url): ?string
    {
        $segments = explode('/', parse_url($url, PHP_URL_PATH));
        return $segments[2] ?? null;
    }
}
