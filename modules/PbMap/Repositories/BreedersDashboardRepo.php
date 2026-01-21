<?php

namespace Modules\PbMap\Repositories;

use Modules\PbMap\Models\Breeder;
use Modules\PbMap\Models\Commodity;

class BreedersDashboardRepo
{
    private function normalizeScope($user, ?string $scope): string
    {
        $scope = $scope ? strtolower(trim($scope)) : '';
        $allowed = ['owned', 'institute', 'public', 'all'];
        if (!in_array($scope, $allowed, true)) {
            $scope = '';
        }

        if (!$user) {
            return 'public';
        }

        if ($user->isAdmin()) {
            return $scope !== '' ? $scope : 'all';
        }

        if ($user->isResearcher()) {
            return 'public';
        }

        if ($user->isFocalPerson()) {
            $default = 'institute';
            $allowedForRole = ['owned', 'institute', 'public'];
            return in_array($scope, $allowedForRole, true) ? $scope : $default;
        }

        if ($user->isBreeder()) {
            $default = 'owned';
            $allowedForRole = ['owned', 'institute', 'public'];
            return in_array($scope, $allowedForRole, true) ? $scope : $default;
        }

        return 'public';
    }

    private function resolveInstituteId($user, ?string $scope, ?int $instituteId): ?int
    {
        if ($scope !== 'institute') {
            return null;
        }

        if ($instituteId) {
            return $instituteId;
        }

        return $user?->affiliation ? (int) $user->affiliation : null;
    }

    private function applyScopeToBreeders($builder, $user, string $scope, ?int $instituteId)
    {
        if ($scope === 'owned' && $user) {
            return $builder->where('breeders.user_id', $user->id);
        }

        if ($scope === 'institute' && $instituteId) {
            return $builder->where('breeders.affiliation', $instituteId);
        }

        // public/all: no additional restriction for breeders
        return $builder;
    }

    private function applyScopeToCommodities($builder, $user, string $scope, ?int $instituteId)
    {
        if ($scope === 'owned' && $user) {
            return $builder->where('commodities.user_id', $user->id);
        }

        if ($scope === 'institute' && $instituteId) {
            $builder = $builder
                ->join('breeders', 'commodities.breeder_id', '=', 'breeders.id')
                ->where('breeders.affiliation', $instituteId);

            // Non-admin/non-focal users should only see approved data
            if (!$user || (!$user->isAdmin() && !$user->isFocalPerson())) {
                $builder->whereNotNull('commodities.approved_at');
            }

            return $builder;
        }

        if ($scope === 'public') {
            return $builder->whereNotNull('commodities.approved_at');
        }

        if ($scope === 'all') {
            // Only admins can see all; others fallback to public
            if ($user && $user->isAdmin()) {
                return $builder;
            }
            return $builder->whereNotNull('commodities.approved_at');
        }

        return $builder;
    }

    public function overview($user, ?string $scopeBy = null, ?int $instituteId = null): array
    {
        $scope = $this->normalizeScope($user, $scopeBy);
        $instituteId = $this->resolveInstituteId($user, $scope, $instituteId);

        $breedersQ = Breeder::query()->join('loc_cities', 'loc_cities.id', '=', 'breeders.geolocation');
        $commoditiesQ = Commodity::query()->join('loc_cities', 'loc_cities.id', '=', 'commodities.geolocation');

        $breedersQ = $this->applyScopeToBreeders($breedersQ, $user, $scope, $instituteId);
        $commoditiesQ = $this->applyScopeToCommodities($commoditiesQ, $user, $scope, $instituteId);

        $totalBreeders = (clone $breedersQ)->count('breeders.id');
        $totalCommodities = (clone $commoditiesQ)->count('commodities.id');

        $regionsCount = (clone $breedersQ)->distinct('loc_cities.regDesc')->count('loc_cities.regDesc');
        $institutesCount = (clone $breedersQ)
            ->join('institutes', 'institutes.id', '=', 'breeders.affiliation')
            ->distinct('institutes.id')
            ->count('institutes.id');

        $breedersByRegion = (clone $breedersQ)
            ->selectRaw('loc_cities.regDesc as label, count(*) as total')
            ->groupBy('loc_cities.regDesc')
            ->orderByDesc('total')
            ->limit(12)
            ->get();

        $commoditiesByName = (clone $commoditiesQ)
            ->selectRaw('commodities.name as label, count(*) as total')
            ->groupBy('commodities.name')
            ->orderByDesc('total')
            ->limit(12)
            ->get();

        return [
            'scope' => [
                'scope_by' => $scope,
                'institute_id' => $instituteId,
            ],
            'totals' => [
                'breeders' => $totalBreeders,
                'commodities' => $totalCommodities,
                'pendingCommodities' => (clone $commoditiesQ)->whereNull('commodities.approved_at')->count(),
                'regions' => $regionsCount,
                'institutes' => $institutesCount,
            ],
            'charts' => [
                'breedersByRegion' => $breedersByRegion,
                'commoditiesByName' => $commoditiesByName,
            ],
        ];
    }

    public function recent($user, ?string $scopeBy = null, ?int $instituteId = null): array
    {
        $scope = $this->normalizeScope($user, $scopeBy);
        $instituteId = $this->resolveInstituteId($user, $scope, $instituteId);

        $recentBreeders = $this->applyScopeToBreeders(
            Breeder::query()->with('affiliated')
                ->join('loc_cities', 'loc_cities.id', '=', 'breeders.geolocation')
                ->select('breeders.*'),
            $user,
            $scope,
            $instituteId
        )
            ->latest('breeders.created_at')
            ->limit(8)
            ->get()
            ->map(function ($b) {
                return [
                    'id' => $b->id,
                    'name' => trim($b->fname . ' ' . $b->lname),
                    'institute' => $b->affiliated?->name,
                    'region' => $b->geolocation?->regDesc ?? null,
                    'created_at' => $b->created_at,
                ];
            });

        $recentCommodities = $this->applyScopeToCommodities(
            Commodity::query()->with(['breeder.affiliated'])
                ->join('loc_cities', 'loc_cities.id', '=', 'commodities.geolocation')
                ->select('commodities.*'),
            $user,
            $scope,
            $instituteId
        )
            ->latest('commodities.created_at')
            ->limit(8)
            ->get()
            ->map(function ($c) {
                return [
                    'id' => $c->id,
                    'name' => $c->name,
                    'variety' => $c->variety,
                    'breeder' => optional($c->breeder)->fname . ' ' . optional($c->breeder)->lname,
                    'institute' => optional($c->breeder?->affiliated)->name,
                    'region' => $c->geolocation?->regDesc ?? null,
                    'created_at' => $c->created_at,
                ];
            });

        return [
            'breeders' => $recentBreeders,
            'commodities' => $recentCommodities,
        ];
    }

    public function myStats($user): array
    {
        if (!$user || !$user->isBreeder()) {
            return [
                'isBreeder' => false,
                'stats' => null,
            ];
        }

        $commodities = Commodity::query()->where('user_id', $user->id);

        return [
            'isBreeder' => true,
            'stats' => [
                'myCommodities' => (clone $commodities)->count(),
                'distinctVarieties' => (clone $commodities)->whereNotNull('variety')->distinct('variety')->count('variety'),
                'withPopulation' => (clone $commodities)->whereNotNull('population')->count(),
            ],
        ];
    }
}
