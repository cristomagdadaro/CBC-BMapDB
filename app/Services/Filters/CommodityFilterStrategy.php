<?php

namespace App\Services\Filters;

use Modules\PbMap\Models\Commodity;
use Modules\PbMap\Scopes\CommodityApprovalScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Filter strategy for commodity data
 */
class CommodityFilterStrategy extends BaseFilterStrategy
{
    public function buildBaseQuery(): Builder
    {
        // Bypass approval scope here to control visibility per request context
        return Commodity::withoutGlobalScope(CommodityApprovalScope::class)
            ->select('commodities.*', 'loc_cities.latitude', 'loc_cities.longitude', 'loc_cities.regDesc', 'loc_cities.provDesc', 'loc_cities.cityDesc')
            ->join('breeders', 'commodities.breeder_id', '=', 'breeders.id')
            ->join('loc_cities', 'breeders.geolocation', '=', 'loc_cities.id')
            ->leftJoin('institutes', 'breeders.affiliation', '=', 'institutes.id');
    }

    public function applyFilters(Builder $query, array $filters): Builder
    {
        // Public users: show only approved commodities
        $query->when(!auth()->check(), function (Builder $q) {
            $q->whereNotNull('commodities.approved_at');
        });

        // Commodity name filter (supports both 'commodity' and 'commodities' keys)
        $commodityFilter = $filters['commodity'] ?? $filters['commodities'] ?? null;
        if (!empty($commodityFilter)) {
            $query->where('commodities.name', $commodityFilter);
        }

        // Hierarchical geographic filtering - Region → Province → City
        if (!empty($filters['regions']) || !empty($filters['region'])) {
            $regionValue = $filters['regions'] ?? $filters['region'];
            $query->where('loc_cities.regDesc', $regionValue);
        }

        if (!empty($filters['provinces']) || !empty($filters['province'])) {
            $provinceValue = $filters['provinces'] ?? $filters['province'];
            $query->where('loc_cities.provDesc', $provinceValue);
        }

        if (!empty($filters['cities']) || !empty($filters['city'])) {
            $cityValue = $filters['cities'] ?? $filters['city'];
            // Cities filter uses ID since that's what the geolocation column references
            $query->where('loc_cities.id', $cityValue);
        }

        // Breeder type filter
        if (!empty($filters['breeder_type'])) {
            $query->where('breeders.breeder_type', $filters['breeder_type']);
        }

        // Institute filter
        if (!empty($filters['institute'])) {
            $query->where('institutes.name', $filters['institute']);
        }

        // Search filter (applies to commodity name and breeder name)
        if (!empty($filters['search'])) {
            $query->where(function($q) use ($filters) {
                $q->where('commodities.name', 'LIKE', '%' . $filters['search'] . '%')
                  ->orWhereRaw("CONCAT_WS(' ', breeders.fname, breeders.mname, breeders.lname, breeders.suffix) LIKE ?",
                      ['%' . $filters['search'] . '%']);
            });
        }


        return $query;
    }

    public function aggregateData(Builder $query, array $filters): array
    {
        $filterBy = $filters['filter_by'] ?? 'city';

        switch ($filterBy) {
            case 'province':
                return $this->aggregateByProvince($query);
            case 'region':
                return $this->aggregateByRegion($query);
            case 'institute':
                return $this->aggregateByInstitute($query);
            default:
                return $this->aggregateByCity($query);
        }
    }

    public function getAvailableOptions(array $currentFilters = []): array
    {
        $options = [
            'commodities' => $this->getCommodityOptions(),
            'breeder_types' => $this->getBreederTypeOptions(),
            'institutes' => $this->getInstituteOptions(),
        ];

        return array_merge($options, $this->getGeographicOptions($currentFilters));
    }

    public function getSummaryStats(Builder $query): array
    {
        // Clone the query and replace the select to avoid mixing individual columns with aggregates
        $statsQuery = clone $query;
        $statsQuery->getQuery()->columns = null; // Clear existing select columns

        $stats = $statsQuery
            ->selectRaw('
                COUNT(DISTINCT commodities.id) as total_commodities,
                COUNT(DISTINCT breeders.id) as total_breeders,
                COUNT(DISTINCT institutes.id) as total_institutes,
                COUNT(DISTINCT loc_cities.regDesc) as total_regions
            ')
            ->first();

        return [
            'total_commodities' => $stats->total_commodities ?? 0,
            'total_breeders' => $stats->total_breeders ?? 0,
            'total_institutes' => $stats->total_institutes ?? 0,
            'total_regions' => $stats->total_regions ?? 0,
        ];
    }

    public function getGeographicDistribution(Builder $query, string $groupBy): array
    {
        $columnMap = [
            'region' => 'loc_cities.regDesc',
            'province' => 'loc_cities.provDesc',
            'city' => 'loc_cities.cityDesc',
            'institute' => 'institutes.name',
        ];

        $column = $columnMap[$groupBy] ?? 'loc_cities.regDesc';

        return $query
            ->selectRaw("{$column} as label, COUNT(*) as total, AVG(loc_cities.latitude) as lat, AVG(loc_cities.longitude) as lng")
            ->groupBy($column)
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }

    protected function getAllowedFilters(): array
    {
        return [
            'commodity', 'institute', 'breeder_type', 'region',
            'province', 'city', 'filter_by'
        ];
    }

    private function aggregateByInstitute(Builder $query): array
    {
        return $query
            ->selectRaw('institutes.name as label, COUNT(*) as total, AVG(loc_cities.latitude) as lat, AVG(loc_cities.longitude) as lng')
            ->whereNotNull('institutes.name')
            ->groupBy('institutes.id', 'institutes.name')
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }

    private function aggregateByRegion(Builder $query): array
    {
        return $query
            ->select(DB::raw('loc_cities.regDesc as label, COUNT(*) as total, AVG(loc_cities.latitude) as lat, AVG(loc_cities.longitude) as lng'))
            ->groupBy('loc_cities.regDesc')
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }

    private function aggregateByProvince(Builder $query): array
    {
        return $query
            ->select(DB::raw('loc_cities.provDesc as label, COUNT(*) as total, AVG(loc_cities.latitude) as lat, AVG(loc_cities.longitude) as lng'))
            ->groupBy('loc_cities.provDesc')
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }

    private function aggregateByCity(Builder $query): array
    {
        return $query
            ->select(DB::raw('loc_cities.id as city_id, loc_cities.cityDesc as label, COUNT(*) as total, AVG(loc_cities.latitude) as lat, AVG(loc_cities.longitude) as lng'))
            ->groupBy('loc_cities.id', 'loc_cities.cityDesc')
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }

    private function aggregateByCommodity(Builder $query): array
    {
        return $query
            ->select(DB::raw('loc_cities.cityDesc as label, COUNT(*) as total, AVG(loc_cities.latitude) as lat, AVG(loc_cities.longitude) as lng'))
            ->groupBy('loc_cities.id', 'loc_cities.cityDesc')
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }


    private function getCommodityOptions(): array
    {
        return Commodity::query()
            ->select('name as value', 'name as label')
            ->whereNotNull('name')
            ->distinct()
            ->orderBy('name')
            ->get()
            ->toArray();
    }

    private function getInstituteOptions(): array
    {
        return DB::table('institutes')
            ->select('name as value', 'name as label')
            ->whereNotNull('name')
            ->distinct()
            ->orderBy('name')
            ->get()
            ->toArray();
    }

    private function getBreederTypeOptions(): array
    {
        return DB::table('breeders')
            ->select('breeder_type as value', 'breeder_type as label')
            ->whereNotNull('breeder_type')
            ->distinct()
            ->orderBy('breeder_type')
            ->get()
            ->toArray();
    }

    /**
     * Context-aware geographic options: provinces filtered by region, cities filtered by province (and region)
     */
    protected function getGeographicOptions(array $currentFilters = []): array
    {
        $region = $currentFilters['region'] ?? $currentFilters['regions'] ?? null;
        $province = $currentFilters['province'] ?? $currentFilters['provinces'] ?? null;

        // Regions with breeders, commodities, or institutes
        $regions = collect(
            \DB::select(<<<SQL
                SELECT DISTINCT regDesc as value, regDesc as label FROM loc_cities
                WHERE regDesc IS NOT NULL AND id IN (SELECT geolocation FROM breeders)
                UNION
                SELECT DISTINCT regDesc as value, regDesc as label FROM loc_cities
                WHERE regDesc IS NOT NULL AND id IN (
                    SELECT breeders.geolocation FROM breeders
                    JOIN commodities ON breeders.id = commodities.breeder_id
                )
                UNION
                SELECT DISTINCT regDesc as value, regDesc as label FROM loc_cities
                WHERE regDesc IS NOT NULL AND id IN (
                    SELECT geolocation FROM breeders WHERE affiliation IN (SELECT id FROM institutes)
                )
            SQL)
        )->unique('value')->sortBy('label')->values()->toArray();

        // Provinces with breeders, commodities, or institutes, filtered by region if set
        $provinceWhere = '';
        if ($region) {
            $provinceWhere = "AND regDesc = '" . str_replace("'", "''", $region) . "'";
        }
        $provinces = collect(
            \DB::select(<<<SQL
                SELECT DISTINCT provDesc as value, provDesc as label FROM loc_cities
                WHERE provDesc IS NOT NULL $provinceWhere AND id IN (SELECT geolocation FROM breeders)
                UNION
                SELECT DISTINCT provDesc as value, provDesc as label FROM loc_cities
                WHERE provDesc IS NOT NULL $provinceWhere AND id IN (
                    SELECT breeders.geolocation FROM breeders
                    JOIN commodities ON breeders.id = commodities.breeder_id
                )
                UNION
                SELECT DISTINCT provDesc as value, provDesc as label FROM loc_cities
                WHERE provDesc IS NOT NULL $provinceWhere AND id IN (
                    SELECT geolocation FROM breeders WHERE affiliation IN (SELECT id FROM institutes)
                )
            SQL)
        )->unique('value')->sortBy('label')->values()->toArray();

        // Cities with breeders, commodities, or institutes, filtered by province (and region) if set
        $cityWhere = '';
        if ($province) {
            $cityWhere .= "AND provDesc = '" . str_replace("'", "''", $province) . "'";
        }
        if ($region) {
            $cityWhere .= " AND regDesc = '" . str_replace("'", "''", $region) . "'";
        }
        $cities = collect(
            \DB::select(<<<SQL
                SELECT DISTINCT id as value, cityDesc as label FROM loc_cities
                WHERE cityDesc IS NOT NULL $cityWhere AND id IN (SELECT geolocation FROM breeders)
                UNION
                SELECT DISTINCT id as value, cityDesc as label FROM loc_cities
                WHERE cityDesc IS NOT NULL $cityWhere AND id IN (
                    SELECT breeders.geolocation FROM breeders
                    JOIN commodities ON breeders.id = commodities.breeder_id
                )
                UNION
                SELECT DISTINCT id as value, cityDesc as label FROM loc_cities
                WHERE cityDesc IS NOT NULL $cityWhere AND id IN (
                    SELECT geolocation FROM breeders WHERE affiliation IN (SELECT id FROM institutes)
                )
            SQL)
        )->unique('value')->sortBy('label')->values()->toArray();

        return [
            'regions' => $regions,
            'provinces' => $provinces,
            'cities' => $cities,
        ];
    }
}
