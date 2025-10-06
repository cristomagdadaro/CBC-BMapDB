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
            ->with(['breeders.geolocation'])
            ->leftJoin('breeders', 'institutes.id', '=', 'breeders.affiliation')
            ->leftJoin('loc_cities', 'breeders.geolocation', '=', 'loc_cities.id');
    }

    public function applyFilters(Builder $query, array $filters): Builder
    {
        // Institute name search
        if (!empty($filters['search'])) {
            $query->where('institutes.name', 'LIKE', '%' . $filters['search'] . '%');
        }

        // Institute type filter
        if (!empty($filters['institute_type'])) {
            $query->where('institutes.type', $filters['institute_type']);
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
        $groupBy = $filters['group_by'] ?? 'region';

        switch ($groupBy) {
            case 'institute_type':
                return $this->aggregateByInstituteType($query);
            case 'province':
                return $this->aggregateByProvince($query);
            case 'city':
                return $this->aggregateByCity($query);
            default:
                return $this->aggregateByRegion($query);
        }
    }

    public function getAvailableOptions(array $currentFilters = []): array
    {
        $options = [
            'institute_types' => $this->getInstituteTypeOptions(),
            'group_by_options' => [
                ['value' => 'region', 'label' => 'Region'],
                ['value' => 'province', 'label' => 'Province'],
                ['value' => 'city', 'label' => 'City'],
                ['value' => 'institute_type', 'label' => 'Institute Type'],
            ]
        ];

        return array_merge($options, $this->getGeographicOptions());
    }

    public function getSummaryStats(Builder $query): array
    {
        $stats = $query->selectRaw('
            COUNT(DISTINCT institutes.id) as total_institutes,
            COUNT(DISTINCT breeders.id) as total_breeders,
            COUNT(DISTINCT loc_cities.regDesc) as total_regions,
            COUNT(DISTINCT institutes.type) as total_institute_types
        ')->first();

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
            'institute_type' => 'institutes.type',
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
            'province', 'city', 'group_by'
        ];
    }

    private function aggregateByInstituteType(Builder $query): array
    {
        return $query
            ->selectRaw('institutes.type as label, COUNT(DISTINCT institutes.id) as total, AVG(institutes.latitude) as lat, AVG(institutes.longitude) as lng')
            ->whereNotNull('institutes.type')
            ->groupBy('institutes.type')
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }

    private function aggregateByRegion(Builder $query): array
    {
        return $query
            ->selectRaw('loc_cities.regDesc as label, COUNT(DISTINCT institutes.id) as total, AVG(loc_cities.latitude) as lat, AVG(loc_cities.longitude) as lng')
            ->whereNotNull('loc_cities.regDesc')
            ->groupBy('loc_cities.regDesc')
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }

    private function aggregateByProvince(Builder $query): array
    {
        return $query
            ->selectRaw('loc_cities.provDesc as label, COUNT(DISTINCT institutes.id) as total, AVG(loc_cities.latitude) as lat, AVG(loc_cities.longitude) as lng')
            ->whereNotNull('loc_cities.provDesc')
            ->groupBy('loc_cities.provDesc')
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }

    private function aggregateByCity(Builder $query): array
    {
        return $query
            ->selectRaw('loc_cities.cityDesc as label, COUNT(DISTINCT institutes.id) as total, loc_cities.latitude as lat, loc_cities.longitude as lng')
            ->whereNotNull('loc_cities.cityDesc')
            ->groupBy('loc_cities.id', 'loc_cities.cityDesc', 'loc_cities.latitude', 'loc_cities.longitude')
            ->orderByDesc('total')
            ->get()
            ->toArray();
    }

    private function getInstituteTypeOptions(): array
    {
        return DB::table('institutes')
            ->select('type as value', 'type as label')
            ->whereNotNull('type')
            ->distinct()
            ->orderBy('type')
            ->get()
            ->toArray();
    }
}
