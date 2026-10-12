<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetActivityLogRequest;
use App\Repository\API\ActivityLogRepo;
use Illuminate\Http\JsonResponse;

class ActivityLogController extends Controller
{
    public function __construct(private ActivityLogRepo $activityLogRepo)
    {
    }

    public function index(GetActivityLogRequest $request): JsonResponse
    {
        $logs = $this->activityLogRepo->getLogs($request->validated(), $request->user());

        return response()->json($logs);
    }
}
