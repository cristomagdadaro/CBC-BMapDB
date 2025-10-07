<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Modules\PbMap\Models\Breeder;
use Modules\PbMap\Models\Commodity;
use Modules\TwgDb\Models\TWGExpert;
use Modules\TwgDb\Models\TWGProject;
use Modules\TwgDb\Models\TWGProduct;
use Modules\TwgDb\Models\TWGService;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $dashboardData = [
            'statistics' => $this->getStatistics($user),
            'recentActivities' => $this->getRecentActivities($user),
        ];

        // Add admin-specific data
        if ($user->isAdmin()) {
            $dashboardData['onlineUsers'] = $this->getOnlineUsers();
            $dashboardData['recentUsers'] = $this->getRecentUsers();
            $dashboardData['systemOverview'] = $this->getSystemOverview();
        }

        // Add role-specific data
        if ($user->isBreeder()) {
            $dashboardData['breederStats'] = $this->getBreederStats($user);
        }

        if ($user->isFocalPerson()) {
            $dashboardData['focalPersonStats'] = $this->getFocalPersonStats();
        }

        if ($user->isResearcher()) {
            $dashboardData['researcherStats'] = $this->getResearcherStats($user);
        }

        return Inertia::render('Dashboard', $dashboardData);
    }

    private function getStatistics($user)
    {
        $stats = [
            'totalBreeders' => Breeder::count(),
            'totalCommodities' => Commodity::count(),
            'totalTWGExperts' => TWGExpert::count(),
            'totalTWGProjects' => TWGProject::count(),
        ];

        if ($user->isBreeder()) {
            $breeder = Breeder::where('user_id', $user->id)->first();
            if ($breeder) {
                $stats['myCommodities'] = Commodity::where('breeder_id', $breeder->id)->count();
            }
        }

        if ($user->isResearcher() || $user->isFocalPerson()) {
            $stats['totalTWGProducts'] = TWGProduct::count();
            $stats['totalTWGServices'] = TWGService::count();
        }

        return $stats;
    }

    private function getRecentActivities($user)
    {
        $activities = [];

        // Get recent commodities
        $recentCommodities = Commodity::with(['breeder', 'user'])
            ->latest('updated_at')
            ->limit(10)
            ->get()
            ->map(function ($commodity) {
                return [
                    'id' => $commodity->id,
                    'type' => 'commodity',
                    'action' => $commodity->wasRecentlyCreated ? 'created' : 'updated',
                    'title' => $commodity->name,
                    'description' => $commodity->variety,
                    'user' => $commodity->user ? $commodity->user->getFullName() : 'Unknown',
                    'timestamp' => $commodity->updated_at,
                    'module' => 'Plant Breeders Map',
                ];
            });

        // Get recent TWG projects
        $recentProjects = TWGProject::with('user')
            ->latest('updated_at')
            ->limit(10)
            ->get()
            ->map(function ($project) {
                return [
                    'id' => $project->id,
                    'type' => 'twg_project',
                    'action' => $project->wasRecentlyCreated ? 'created' : 'updated',
                    'title' => $project->title,
                    'description' => $project->project_leader,
                    'user' => $project->user ? $project->user->getFullName() : 'Unknown',
                    'timestamp' => $project->updated_at,
                    'module' => 'TWG Biotech Database',
                ];
            });

        // Get recent breeders
        $recentBreeders = Breeder::with('user')
            ->latest('updated_at')
            ->limit(10)
            ->get()
            ->map(function ($breeder) {
                return [
                    'id' => $breeder->id,
                    'type' => 'breeder',
                    'action' => $breeder->wasRecentlyCreated ? 'created' : 'updated',
                    'title' => $breeder->fname . ' ' . $breeder->lname,
                    'description' => $breeder->affiliation,
                    'user' => $breeder->user ? $breeder->user->getFullName() : 'System',
                    'timestamp' => $breeder->updated_at,
                    'module' => 'Plant Breeders Map',
                ];
            });

        // Merge and sort by timestamp
        $activities = collect()
            ->merge($recentCommodities)
            ->merge($recentProjects)
            ->merge($recentBreeders)
            ->sortByDesc('timestamp')
            ->take(15)
            ->values()
            ->all();

        return $activities;
    }

    private function getOnlineUsers()
    {
        // Users active in the last 5 minutes
        $onlineThreshold = now()->subMinutes(5);

        return User::where('last_activity_at', '>=', $onlineThreshold)
            ->with('roles')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->getFullName(),
                    'email' => $user->email,
                    'role' => $user->getRole(),
                    'last_activity' => $user->last_activity_at,
                    'profile_photo_url' => $user->profile_photo_url,
                ];
            });
    }

    private function getRecentUsers()
    {
        return User::with('roles')
            ->latest('created_at')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->getFullName(),
                    'email' => $user->email,
                    'role' => $user->getRole(),
                    'created_at' => $user->created_at,
                    'profile_photo_url' => $user->profile_photo_url,
                ];
            });
    }

    private function getSystemOverview()
    {
        return [
            'totalUsers' => User::count(),
            'activeUsers' => User::where('last_activity_at', '>=', now()->subDays(7))->count(),
            'totalAdmins' => User::role('admin')->count(),
            'totalBreeders' => User::role('breeder')->count(),
            'totalFocalPersons' => User::role('focal person')->count(),
            'totalResearchers' => User::role('researcher')->count(),
            'recentRegistrations' => User::where('created_at', '>=', now()->subDays(7))->count(),

            // Module-specific stats
            'pbmap' => [
                'totalBreeders' => Breeder::count(),
                'totalCommodities' => Commodity::count(),
                'recentCommodities' => Commodity::where('created_at', '>=', now()->subDays(7))->count(),
            ],
            'twgdb' => [
                'totalExperts' => TWGExpert::count(),
                'totalProjects' => TWGProject::count(),
                'totalProducts' => TWGProduct::count(),
                'totalServices' => TWGService::count(),
                'recentProjects' => TWGProject::where('created_at', '>=', now()->subDays(7))->count(),
            ],
        ];
    }

    private function getBreederStats($user)
    {
        $breeder = Breeder::where('user_id', $user->id)->first();

        if (!$breeder) {
            return null;
        }

        return [
            'totalCommodities' => Commodity::where('breeder_id', $breeder->id)->count(),
            'recentCommodities' => Commodity::where('breeder_id', $breeder->id)
                ->latest('updated_at')
                ->limit(5)
                ->get()
                ->map(function ($commodity) {
                    return [
                        'id' => $commodity->id,
                        'name' => $commodity->name,
                        'variety' => $commodity->variety,
                        'updated_at' => $commodity->updated_at,
                    ];
                }),
        ];
    }

    private function getFocalPersonStats()
    {
        return [
            'pendingApprovals' => 0, // Implement based on your approval system
            'totalInstitutions' => DB::table('institutes')->count(),
            'recentActivities' => Commodity::latest('updated_at')->limit(5)->count(),
        ];
    }

    private function getResearcherStats($user)
    {
        $expert = TWGExpert::where('user_id', $user->id)->first();

        return [
            'availableCommodities' => Commodity::count(),
            'availableProjects' => TWGProject::count(),
            'myProjects' => $expert ? TWGProject::where('user_id', $user->id)->count() : 0,
        ];
    }

    public function updateActivity(Request $request)
    {
        $request->user()->update([
            'last_activity_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}

