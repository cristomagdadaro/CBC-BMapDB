<?php

namespace App\Http\Controllers;

use App\Repository\API\AccountsRepo;
use App\Repository\API\DashboardRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class DashboardController extends BaseController
{
    public function __construct(
        protected DashboardRepo $dashboardRepo,
        protected AccountsRepo $accountsRepo,
    ) {
        $this->service = $dashboardRepo;
    }

    public function index(Request $request): Response|SymfonyResponse
    {
        $user = $request->user();

        if (!$this->dashboardRepo->canAccessSystemDashboard($user)) {
            $landingRoute = $this->accountsRepo->getUserLandingRoute($user);
            return redirect()->route($landingRoute);
        }

        return Inertia::render('Dashboard');
    }

    public function updateActivity(Request $request): JsonResponse
    {
        $this->dashboardRepo->updateUserActivity($request->user());

        return response()->json(['success' => true]);
    }
}
