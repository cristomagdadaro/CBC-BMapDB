<?php

namespace Modules\PbMap\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\PbMap\Models\Breeder;
use Modules\PbMap\Models\Commodity;

class BreedersDashboardController extends Controller
{
    private function scopeByRole($builder, $user, string $entity = 'commodities')
    {
        if (!$user) return $builder;

        if ($user->isAdmin()) return $builder; // full access

        if ($user->isBreeder()) {
            // Owned records
            return $builder->where($entity . '.user_id', $user->id);
        }

        if ($user->isFocalPerson()) {
            // Filter by user's institute
            if ($entity === 'commodities') {
                return $builder
                    ->join('breeders', 'commodities.breeder_id', '=', 'breeders.id')
                    ->where('breeders.affiliation', $user->affiliation);
            }
            if ($entity === 'breeders') {
                return $builder->where('breeders.affiliation', $user->affiliation);
            }
        }

        // Researchers and others: default to full dataset
        return $builder;
    }

    public function overview(Request $request)
    {
        $user = $request->user();

        // Base builders
        $breedersQ = Breeder::query()->join('loc_cities', 'loc_cities.id', '=', 'breeders.geolocation');
        $commoditiesQ = Commodity::query()
            ->join('loc_cities', 'loc_cities.id', '=', 'commodities.geolocation');

        // Role scoping
        $breedersQ = $this->scopeByRole($breedersQ, $user, 'breeders');
        $commoditiesQ = $this->scopeByRole($commoditiesQ, $user, 'commodities');

        // Totals
        $totalBreeders = (clone $breedersQ)->count('breeders.id');
        $totalCommodities = (clone $commoditiesQ)->count('commodities.id');

        // Distinct counts
        $regionsCount = (clone $breedersQ)->distinct('loc_cities.regDesc')->count('loc_cities.regDesc');
        $institutesCount = (clone $breedersQ)
            ->join('institutes', 'institutes.id', '=', 'breeders.affiliation')
            ->distinct('institutes.id')
            ->count('institutes.id');

        // Charts
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

        return response()->json([
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
        ]);
    }

    public function recent(Request $request)
    {
        $user = $request->user();

        $recentBreeders = $this->scopeByRole(
            Breeder::query()->with('affiliated')
                ->join('loc_cities', 'loc_cities.id', '=', 'breeders.geolocation')
                ->select('breeders.*'),
            $user,
            'breeders'
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

        $recentCommodities = $this->scopeByRole(
            Commodity::query()->with(['breeder.affiliated'])
                ->join('loc_cities', 'loc_cities.id', '=', 'commodities.geolocation')
                ->select('commodities.*'),
            $user,
            'commodities'
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

        return response()->json([
            'breeders' => $recentBreeders,
            'commodities' => $recentCommodities,
        ]);
    }

    public function myStats(Request $request)
    {
        $user = $request->user();

        if (!$user || !$user->isBreeder()) {
            return response()->json([
                'isBreeder' => false,
                'stats' => null,
            ]);
        }

        $commodities = Commodity::query()->where('user_id', $user->id);

        return response()->json([
            'isBreeder' => true,
            'stats' => [
                'myCommodities' => (clone $commodities)->count(),
                'distinctVarieties' => (clone $commodities)->whereNotNull('variety')->distinct('variety')->count('variety'),
                'withPopulation' => (clone $commodities)->whereNotNull('population')->count(),
            ],
        ]);
    }
}

