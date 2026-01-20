<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repository\API\DashboardRepo;
use Illuminate\Http\Request;

class DashboardApiController extends Controller
{
    protected DashboardRepo $dashboardRepo;

    public function __construct(DashboardRepo $dashboardRepo)
    {
        $this->dashboardRepo = $dashboardRepo;
    }

    public function getSystemStats(Request $request)
    {
        return response()->json($this->dashboardRepo->getSystemStats());
    }

    public function getOnlineUsers(Request $request)
    {
        $user = $request->user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($this->dashboardRepo->getOnlineUsers());
    }

    public function getRecentUsers(Request $request)
    {
        $user = $request->user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($this->dashboardRepo->getRecentUsers());
    }

    public function getUserRoleDistribution(Request $request)
    {
        $user = $request->user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($this->dashboardRepo->getUserRoleDistribution());
    }

    public function getSystemActivities(Request $request)
    {
        $user = $request->user();

        return response()->json($this->dashboardRepo->getSystemActivities());
    }

    public function updateActivity(Request $request)
    {
        $request->user()->update([
            'last_activity_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}
