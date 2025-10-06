<?php

namespace App\Services\Filters;

use App\Models\Commodity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Filter strategy for commodity data
 */
class CommodityFilterStrategy extends BaseFilterStrategy
{
    public function buildBaseQuery(): Builder
    {
        return Commodity::query()
            ->with(['breeder.geolocation', 'breeder.institute'])
            ->join('breeders', 'commodities.breeder_id', '=', 'breeders.id')
            ->join('loc_cities', 'breeders.geolocation', '=', 'loc_cities.id')
            ->leftJoin('institutes', 'breeders.affiliation', '=', 'institutes.id');
    }

    public function applyFilters(Builder $query, array $filters): Builder
    {
        // Commodity name filter
        if (!empty($filters['commodity'])) {
            $query->where('commodities.name', $filters['commodity']);
        }

        // Institute/affiliation filter
        if (!empty($filters['institute'])) {
            $query->where('institutes.name', $filters['institute']);
        }

        // Breeder type filter
        if (!empty($filters['breeder_type'])) {
            $query->where('breeders.breeder_type', $filters['breeder_type']);
        }

        // Apply geographic filters
        $query = $this->applyGeographicFilters($query, $filters);

        return $query;
    }

    public function aggregateData(Builder $query, array $filters): array
    {
        $groupBy = $filters['group_by'] ?? 'region';

        switch ($groupBy) {
            case 'institute':
                return $this->aggregateByInstitute($query);
            case 'province':
                return $this->aggregateByProvince($query);
            case 'city':
                return $this->aggregateByCity($query);
            case 'commodity':
                return $this->aggregateByCommodity($query);
            default:
                return $this->aggregateByRegion($query);
        }
    }

    public function getAvailableOptions(array $currentFilters = []): array
    {
        $options = [
            'commodities' => $this->getCommodityOptions(),
            'institutes' => $this->getInstituteOptions(),
            'breeder_types' => $this->getBreederTypeOptions(),
            'group_by_options' => [
                ['value' => 'region', 'label' => 'Region'],
                ['value' => 'province', 'label' => 'Province'],
                ['value' => 'city', 'label' => 'City'],
                ['value' => 'institute', 'label' => 'Institute'],
                ['value' => 'commodity', 'label' => 'Commodity'],
            ]
        ];

        return array_merge($options, $this->getGeographicOptions());
    }

    public function getSummaryStats(Builder $query): array
    {
        $stats = $query->selectRaw('
            COUNT(DISTINCT commodities.id) as total_commodities,
            COUNT(DISTINCT breeders.id) as total_breeders,
            COUNT(DISTINCT institutes.id) as total_institutes,
            COUNT(DISTINCT loc_cities.regDesc) as total_regions
        ')->first();

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
            'province', 'city', 'group_by'
        ];
    }

    private function aggregateByInstitute(Builder $query): array
    {
        return $query
            ->selectRaw('institutes.name as label, COUNT(*) as total, institutes.latitude as lat, institutes.longitude as lng')
            ->whereNotNull('institutes.name')
            ->groupBy('institutes.id', 'institutes.name', 'institutes.latitude', 'institutes.longitude')
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }

    private function aggregateByRegion(Builder $query): array
    {
        return $query
            ->selectRaw('loc_cities.regDesc as label, COUNT(*) as total, AVG(loc_cities.latitude) as lat, AVG(loc_cities.longitude) as lng')
            ->groupBy('loc_cities.regDesc')
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }

    private function aggregateByProvince(Builder $query): array
    {
        return $query
            ->selectRaw('loc_cities.provDesc as label, COUNT(*) as total, AVG(loc_cities.latitude) as lat, AVG(loc_cities.longitude) as lng')
            ->groupBy('loc_cities.provDesc')
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }

    private function aggregateByCity(Builder $query): array
    {
        return $query
            ->selectRaw('loc_cities.cityDesc as label, COUNT(*) as total, loc_cities.latitude as lat, loc_cities.longitude as lng')
            ->groupBy('loc_cities.id', 'loc_cities.cityDesc', 'loc_cities.latitude', 'loc_cities.longitude')
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }

    private function aggregateByCommodity(Builder $query): array
    {
        return $query
            ->selectRaw('commodities.name as label, COUNT(*) as total, AVG(loc_cities.latitude) as lat, AVG(loc_cities.longitude) as lng')
            ->groupBy('commodities.name')
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }

    private function getCommodityOptions(): array
    {
        return DB::table('commodities')
            ->select('name as value', 'name as label')
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
}
