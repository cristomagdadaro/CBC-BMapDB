<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ApiRequestLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $perPage = (int) ($request->input('per_page', 15));
        if ($perPage < 1) {
            $perPage = 15;
        }
        if ($perPage > 100) {
            $perPage = 100;
        }

        $query = ApiRequestLog::query()->latest();

        $mineOnly = $request->boolean('mine', true);
        if ($mineOnly || !$user || !$user->isAdmin()) {
            $query->where('user_id', $user?->id);
        } elseif ($request->filled('user_id')) {
            $query->where('user_id', (int) $request->input('user_id'));
        }

        if ($request->filled('method')) {
            $query->where('method', strtoupper($request->input('method')));
        }

        $query->whereNotIn('method', ['GET', 'HEAD', 'OPTIONS']);

        if ($request->filled('model')) {
            $query->where('model', $request->input('model'));
        }

        $logs = $query->paginate($perPage);

        return response()->json($logs);
    }
}
