<?php

namespace App\Services\Filters;

use App\Models\Institute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Filter strategy for institute data
 */
class InstituteFilterStrategy extends BaseFilterStrategy
{
    public function buildBaseQuery(): Builder
    {
        return Institute::query()
            ->select('institutes.*', 'loc_cities.latitude', 'loc_cities.longitude', 'loc_cities.regDesc', 'loc_cities.provDesc', 'loc_cities.cityDesc')
            ->leftJoin('breeders', 'institutes.id', '=', 'breeders.affiliation')
            ->leftJoin('loc_cities', 'breeders.geolocation', '=', 'loc_cities.id')
            ->distinct();
    }

    public function applyFilters(Builder $query, array $filters): Builder
    {
        // Institute name search
        if (!empty($filters['search'])) {
            $query->where('institutes.name', 'LIKE', '%' . $filters['search'] . '%');
        }

        // Institute type filter
        if (!empty($filters['institute_type'])) {
            $query->where('institutes.inst_type', $filters['institute_type']);
        }

        // Apply geographic filters through breeders
        if (!empty($filters['region'])) {
            $query->where('loc_cities.regDesc', $filters['region']);
        }

        if (!empty($filters['province'])) {
            $query->where('loc_cities.provDesc', $filters['province']);
        }

        if (!empty($filters['city'])) {
            $query->where('loc_cities.cityDesc', $filters['city']);
        }

        return $query;
    }

    public function aggregateData(Builder $query, array $filters): array
    {
        $filterBy = $filters['filter_by'] ?? 'institute';

        switch ($filterBy) {
            case 'province':
                return $this->aggregateByProvince($query);
            case 'city':
                return $this->aggregateByCity($query);
            case 'region':
                return $this->aggregateByRegion($query);
            default:
                // For institutes, default to showing individual institutes with their details
                return $this->aggregateByInstitute($query);
        }
    }

    public function getAvailableOptions(array $currentFilters = []): array
    {
        $options = [
            'institute_types' => $this->getInstituteTypeOptions(),
        ];

        return array_merge($options, $this->getGeographicOptions());
    }

    public function getSummaryStats(Builder $query): array
    {
        // Create a fresh query for aggregation to avoid GROUP BY issues
        $stats = Institute::query()
            ->leftJoin('breeders', 'institutes.id', '=', 'breeders.affiliation')
            ->leftJoin('loc_cities', 'breeders.geolocation', '=', 'loc_cities.id')
            ->selectRaw('
                COUNT(DISTINCT institutes.id) as total_institutes,
                COUNT(DISTINCT breeders.id) as total_breeders,
                COUNT(DISTINCT loc_cities.regDesc) as total_regions,
                COUNT(DISTINCT institutes.inst_type) as total_institute_types
            ')
            ->first();

        return [
            'total_institutes' => $stats->total_institutes ?? 0,
            'total_breeders' => $stats->total_breeders ?? 0,
            'total_regions' => $stats->total_regions ?? 0,
            'total_institute_types' => $stats->total_institute_types ?? 0,
        ];
    }

    public function getGeographicDistribution(Builder $query, string $groupBy): array
    {
        $columnMap = [
            'region' => 'loc_cities.regDesc',
            'province' => 'loc_cities.provDesc',
            'city' => 'loc_cities.cityDesc',
            'institute_type' => 'institutes.inst_type',
        ];

        $column = $columnMap[$groupBy] ?? 'loc_cities.regDesc';

        return $query
            ->selectRaw("{$column} as label, COUNT(DISTINCT institutes.id) as total, AVG(loc_cities.latitude) as lat, AVG(loc_cities.longitude) as lng")
            ->whereNotNull($column)
            ->groupBy($column)
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }

    protected function getAllowedFilters(): array
    {
        return [
            'search', 'institute_type', 'region',
            'province', 'city', 'filter_by'
        ];
    }

    private function aggregateByInstituteType(Builder $query): array
    {
        return $query
            ->selectRaw('institutes.inst_type as label, COUNT(DISTINCT institutes.id) as total, AVG(institutes.latitude) as lat, AVG(institutes.longitude) as lng')
            ->whereNotNull('institutes.inst_type')
            ->groupBy('institutes.inst_type')
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }

    private function aggregateByInstitute(Builder $query): array
    {
        // Get individual institutes with their breeder and commodity counts
        // Institutes get their coordinates from the loc_cities table via geolocation field
        $institutes = Institute::query()
            ->select(
                'institutes.id',
                'institutes.name',
                'institutes.geolocation',
                'city.latitude',
                'city.longitude',
                DB::raw('COUNT(DISTINCT breeders.id) as breeder_count'),
                DB::raw('COUNT(DISTINCT commodities.id) as commodity_count')
            )
            ->leftJoin('loc_cities as city', 'institutes.geolocation', '=', 'city.id')
            ->leftJoin('breeders', 'institutes.id', '=', 'breeders.affiliation')
            ->leftJoin('commodities', 'breeders.id', '=', 'commodities.breeder_id')
            ->whereNotNull('city.latitude')
            ->whereNotNull('city.longitude')
            ->groupBy('institutes.id', 'institutes.name', 'institutes.geolocation', 'city.latitude', 'city.longitude')
            ->get();

        return $institutes->map(function($institute) {
            return [
                'label' => $institute->name . ' (' . $institute->breeder_count . ' breeders, ' . $institute->commodity_count . ' commodities)',
                'lat' => (float) $institute->latitude,
                'lng' => (float) $institute->longitude,
                'total' => $institute->breeder_count + $institute->commodity_count,
                'breeder_count' => $institute->breeder_count,
                'commodity_count' => $institute->commodity_count,
            ];
        })->toArray();
    }

    private function aggregateByRegion(Builder $query): array
    {
        return $query
            ->select(DB::raw('loc_cities.regDesc as label, COUNT(DISTINCT institutes.id) as total, AVG(loc_cities.latitude) as lat, AVG(loc_cities.longitude) as lng'))
            ->whereNotNull('loc_cities.regDesc')
            ->groupBy('loc_cities.regDesc')
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }

    private function aggregateByProvince(Builder $query): array
    {
        return $query
            ->select(DB::raw('loc_cities.provDesc as label, COUNT(DISTINCT institutes.id) as total, AVG(loc_cities.latitude) as lat, AVG(loc_cities.longitude) as lng'))
            ->whereNotNull('loc_cities.provDesc')
            ->groupBy('loc_cities.provDesc')
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }

    private function aggregateByCity(Builder $query): array
    {
        return $query
            ->select(DB::raw('loc_cities.cityDesc as label, COUNT(DISTINCT institutes.id) as total, AVG(loc_cities.latitude) as lat, AVG(loc_cities.longitude) as lng'))
            ->whereNotNull('loc_cities.cityDesc')
            ->groupBy('loc_cities.id', 'loc_cities.cityDesc')
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }

    private function getInstituteTypeOptions(): array
    {
        return DB::table('institutes')
            ->select('inst_type as value', 'inst_type as label')
            ->whereNotNull('inst_type')
            ->distinct()
            ->orderBy('inst_type')
            ->get()
            ->toArray();
    }
}
