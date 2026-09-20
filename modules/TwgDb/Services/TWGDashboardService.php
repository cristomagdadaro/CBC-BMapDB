<?php

namespace Modules\TwgDb\Services;

use Illuminate\Support\Facades\DB;
use Modules\TwgDb\Models\TWGExpert;
use Modules\TwgDb\Models\TWGProduct;
use Modules\TwgDb\Models\TWGProject;
use Modules\TwgDb\Models\TWGService;

class TWGDashboardService
{
    public function getSummaryData(): array
    {
        $user = auth()->user();

        if ($user && $user->hasRole(\App\Enums\Role::ADMIN->value)) {
            return [
                'totalExperts' => TWGExpert::count(),
                'totalProjects' => TWGProject::count(),
                'totalProducts' => TWGProduct::count(),
                'totalServices' => TWGService::count(),
                'typeServices' => TWGService::select('type', DB::raw('count(*) as total'))->groupBy('type')->pluck('total', 'type'),
                'topExperts' => TWGExpert::select('twg_expert.id', 'twg_expert.name', DB::raw('COUNT(twg_project.id) as project_count'))
                    ->join('twg_project', 'twg_expert.institution', '=', 'twg_project.institution')
                    ->groupBy('twg_expert.id', 'twg_expert.name')
                    ->orderByDesc('project_count')
                    ->limit(5)
                    ->pluck('project_count', 'name'),
                'totalOnGoingProjects' => TWGProject::select('status', DB::raw('count(*) as total'))->groupBy('status')->pluck('total', 'status'),
            ];
        }

        if (!$user) {
            return [];
        }

        // Filter for authenticated user
        $totalExperts = TWGExpert::ownedByUser($user)->ownedByAffiliation($user)->count();
        $totalProjects = TWGProject::ownedByUser($user)->ownedByAffiliation($user)->count();
        $totalProducts = TWGProduct::ownedByUser($user)->ownedByAffiliation($user)->count();
        $totalServices = TWGService::ownedByUser($user)->ownedByAffiliation($user)->count();

        // Get top 5 experts based on project count
        $topExperts = TWGExpert::select('twg_expert.id', 'twg_expert.name', DB::raw('COUNT(twg_project.id) as project_count'))
            ->join('twg_project', 'twg_expert.institution', '=', 'twg_project.institution')
            ->groupBy('twg_expert.id', 'twg_expert.name')
            ->orderByDesc('project_count')
            ->limit(5)
            ->pluck('project_count', 'name');

        // Group projects by status and count them
        $totalOnGoingProjects = TWGProject::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'totalExperts' => $totalExperts,
            'totalProjects' => $totalProjects,
            'totalProducts' => $totalProducts,
            'totalServices' => $totalServices,
            'typeServices' => TWGService::select('type', DB::raw('count(*) as total'))
                ->ownedByUser($user)
                ->ownedByAffiliation($user)
                ->groupBy('type')
                ->pluck('total', 'type'),
            'topExperts' => $topExperts,
            'totalOnGoingProjects' => $totalOnGoingProjects,
        ];
    }
}
