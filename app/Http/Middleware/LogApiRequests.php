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
        // Proceed with the request and get the response
        $response = $next($request);

        // Capture required data
        $userId = Auth::id(); // Logged-in user ID
        $userRole = Auth::user() ? Auth::user()->getRole() : null; // Logged-in user role
        $ip_address = $request->ip(); // Client IP address
        $method = $request->method(); // HTTP method
        $url = $request->fullUrl(); // Full request URL
        $model = $this->getModelFromUrl($url); // Extract model from URL
        $data = $request->all(); // Request data
        $modifiedId = $data['id'] ?? null; // Assume 'id' key for modified entity

        // Save the log
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
        // Assuming the URL contains the model as a resource (e.g., /api/posts/123)
        $segments = explode('/', parse_url($url, PHP_URL_PATH));
        return $segments[2] ?? null; // Adjust index based on your API structure
    }
}
