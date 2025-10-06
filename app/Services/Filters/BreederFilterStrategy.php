<?php

namespace App\Services\Filters;

use App\Models\Breeder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

/**
 * Filter strategy for breeder data
 */
class BreederFilterStrategy extends BaseFilterStrategy
{
    public function buildBaseQuery(): Builder
    {
        return Breeder::query()
            ->with(['geolocation', 'institute', 'commodities'])
            ->join('loc_cities', 'breeders.geolocation', '=', 'loc_cities.id')
            ->leftJoin('institutes', 'breeders.affiliation', '=', 'institutes.id');
    }

    public function applyFilters(Builder $query, array $filters): Builder
    {
        // Institute/affiliation filter
        if (!empty($filters['institute'])) {
            $query->where('institutes.name', $filters['institute']);
        }

        // Breeder type filter
        if (!empty($filters['breeder_type'])) {
            $query->where('breeders.breeder_type', $filters['breeder_type']);
        }

        // Name search
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->whereRaw("CONCAT_WS(' ', breeders.fname, breeders.mname, breeders.lname, breeders.suffix) LIKE ?",
                    ['%' . $filters['search'] . '%']);
            });
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
            case 'breeder_type':
                return $this->aggregateByBreederType($query);
            default:
                return $this->aggregateByRegion($query);
        }
    }

    public function getAvailableOptions(array $currentFilters = []): array
    {
        $options = [
            'institutes' => $this->getInstituteOptions(),
            'breeder_types' => $this->getBreederTypeOptions(),
            'group_by_options' => [
                ['value' => 'region', 'label' => 'Region'],
                ['value' => 'province', 'label' => 'Province'],
                ['value' => 'city', 'label' => 'City'],
                ['value' => 'institute', 'label' => 'Institute'],
                ['value' => 'breeder_type', 'label' => 'Breeder Type'],
            ]
        ];

        return array_merge($options, $this->getGeographicOptions());
    }

    public function getSummaryStats(Builder $query): array
    {
        $stats = $query->selectRaw('
            COUNT(DISTINCT breeders.id) as total_breeders,
            COUNT(DISTINCT institutes.id) as total_institutes,
            COUNT(DISTINCT loc_cities.regDesc) as total_regions,
            COUNT(DISTINCT breeders.breeder_type) as total_breeder_types
        ')->first();

        return [
            'total_breeders' => $stats->total_breeders ?? 0,
            'total_institutes' => $stats->total_institutes ?? 0,
            'total_regions' => $stats->total_regions ?? 0,
            'total_breeder_types' => $stats->total_breeder_types ?? 0,
        ];
    }

    public function getGeographicDistribution(Builder $query, string $groupBy): array
    {
        $columnMap = [
            'region' => 'loc_cities.regDesc',
            'province' => 'loc_cities.provDesc',
            'city' => 'loc_cities.cityDesc',
            'institute' => 'institutes.name',
            'breeder_type' => 'breeders.breeder_type',
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
            'institute', 'breeder_type', 'search', 'region',
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

    private function aggregateByBreederType(Builder $query): array
    {
        return $query
            ->selectRaw('breeders.breeder_type as label, COUNT(*) as total, AVG(loc_cities.latitude) as lat, AVG(loc_cities.longitude) as lng')
            ->whereNotNull('breeders.breeder_type')
            ->groupBy('breeders.breeder_type')
            ->orderByDesc('total')
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
