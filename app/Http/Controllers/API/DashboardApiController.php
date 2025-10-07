<?php

namespace App\Http\Controllers\API;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardApiController extends Controller
{
    public function getSystemStats(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'totalUsers' => User::count(),
            'activeUsers' => User::where('last_activity_at', '>=', now()->subDays(7))->count(),
            'onlineUsers' => User::where('last_activity_at', '>=', now()->subMinutes(5))->count(),
            'recentRegistrations' => User::where('created_at', '>=', now()->subDays(30))->count(),
            'totalAdmins' => User::role(Role::ADMIN->value)->count(),
            'totalBreeders' => User::role(Role::BREEDER->value)->count(),
            'totalFocalPersons' => User::role(Role::FOCAL_PERSON->value)->count(),
            'totalResearchers' => User::role(Role::RESEARCHER->value)->count(),
        ]);
    }

    public function getOnlineUsers(Request $request)
    {
        $user = $request->user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $onlineThreshold = now()->subMinutes(5);

        $onlineUsers = User::where('last_activity_at', '>=', $onlineThreshold)
            ->with('roles')
            ->orderBy('last_activity_at', 'desc')
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

        return response()->json($onlineUsers);
    }

    public function getRecentUsers(Request $request)
    {
        $user = $request->user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $recentUsers = User::with('roles')
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

        return response()->json($recentUsers);
    }

    public function getUserRoleDistribution(Request $request)
    {
        $user = $request->user();

        if (!$user->isAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'totalUsers' => User::count(),
            'admins' => User::role(Role::ADMIN->value)->count(),
            'breeders' => User::role(Role::BREEDER->value)->count(),
            'focalPersons' => User::role(Role::FOCAL_PERSON->value)->count(),
            'researchers' => User::role(Role::RESEARCHER->value)->count(),
        ]);
    }

    public function getSystemActivities(Request $request)
    {
        $user = $request->user();

        // Get recent user registrations
        $recentUsers = User::with('roles')
            ->latest('created_at')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'type' => 'user_registration',
                    'action' => 'registered',
                    'title' => $user->getFullName(),
                    'description' => 'New user registration',
                    'user' => $user->getFullName(),
                    'role' => $user->getRole(),
                    'timestamp' => $user->created_at,
                    'module' => 'System',
                ];
            });

        // Get recent logins (users who were active recently)
        $recentLogins = User::with('roles')
            ->whereNotNull('last_activity_at')
            ->where('last_activity_at', '>=', now()->subHours(24))
            ->orderBy('last_activity_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'type' => 'user_activity',
                    'action' => 'active',
                    'title' => $user->getFullName(),
                    'description' => 'Recent activity',
                    'user' => $user->getFullName(),
                    'role' => $user->getRole(),
                    'timestamp' => $user->last_activity_at,
                    'module' => 'System',
                ];
            });

        // Merge and sort by timestamp
        $activities = collect()
            ->merge($recentUsers)
            ->merge($recentLogins)
            ->sortByDesc('timestamp')
            ->take(15)
            ->values()
            ->all();

        return response()->json($activities);
    }

    public function updateActivity(Request $request)
    {
        $request->user()->update([
            'last_activity_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}
