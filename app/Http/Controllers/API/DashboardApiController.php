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
        return response()->json($this->dashboardRepo->getOnlineUsers());
    }

    public function getRecentUsers(Request $request)
    {
        return response()->json($this->dashboardRepo->getRecentUsers());
    }

    public function getUserRoleDistribution(Request $request)
    {
        return response()->json($this->dashboardRepo->getUserRoleDistribution());
    }

    public function getSystemActivities(Request $request)
    {
        return response()->json($this->dashboardRepo->getSystemActivities());
    }

    public function updateActivity(Request $request)
    {
        $this->dashboardRepo->updateUserActivity($request->user());

        return response()->json(['success' => true]);
    }
}
