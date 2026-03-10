<?php

namespace App\Services\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * Base strategy for data filtering
 * All specific filter strategies should extend this class
 */
abstract class BaseFilterStrategy
{
    /**
     * Build the base query for this data type
     */
    abstract public function buildBaseQuery(): Builder;

    /**
     * Apply filters to the query
     */
    abstract public function applyFilters(Builder $query, array $filters): Builder;

    /**
     * Aggregate data for map plotting
     */
    abstract public function aggregateData(Builder $query, array $filters): array;

    /**
     * Get available filter options
     */
    abstract public function getAvailableOptions(array $currentFilters = []): array;

    /**
     * Get summary statistics
     */
    abstract public function getSummaryStats(Builder $query): array;

    /**
     * Get geographic distribution
     */
    abstract public function getGeographicDistribution(Builder $query, string $groupBy): array;

    /**
     * Validate filter parameters
     */
    public function validateFilters(array $filters): array
    {
        $errors = [];
        $allowedFilters = $this->getAllowedFilters();

        foreach ($filters as $key => $value) {
            if (!in_array($key, $allowedFilters)) {
                $errors[] = "Invalid filter: {$key}";
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Get list of allowed filters for this strategy
     */
    abstract protected function getAllowedFilters(): array;

    /**
     * Apply geographic filters (shared logic)
     */
    protected function applyGeographicFilters(Builder $query, array $filters): Builder
    {
        if (isset($filters['region'])) {
            $query->whereHas('geolocation', function ($q) use ($filters) {
                $q->where('regDesc', $filters['region']);
            });
        }

        if (isset($filters['province'])) {
            $query->whereHas('geolocation', function ($q) use ($filters) {
                $q->where('provDesc', $filters['province']);
            });
        }

        if (isset($filters['city'])) {
            $query->whereHas('geolocation', function ($q) use ($filters) {
                $q->where('cityDesc', $filters['city']);
            });
        }

        return $query;
    }

    /**
     * Get geographic options (shared logic)
     * Only include regions, provinces, and cities that have at least one breeder or commodity
     */
    protected function getGeographicOptions(): array
    {
        // Regions with breeders or commodities
        $regions = \DB::table('loc_cities')
            ->join('breeders', 'loc_cities.id', '=', 'breeders.geolocation')
            ->join('commodities', 'breeders.id', '=', 'commodities.breeder_id')
            ->select('loc_cities.regDesc as value', 'loc_cities.regDesc as label')
            ->whereNotNull('loc_cities.regDesc')
            ->groupBy('loc_cities.regDesc')
            ->orderBy('loc_cities.regDesc')
            ->get()
            ->toArray();

        // Provinces with breeders or commodities
        $provinces = \DB::table('loc_cities')
            ->join('breeders', 'loc_cities.id', '=', 'breeders.geolocation')
            ->join('commodities', 'breeders.id', '=', 'commodities.breeder_id')
            ->select('loc_cities.provDesc as value', 'loc_cities.provDesc as label')
            ->whereNotNull('loc_cities.provDesc')
            ->groupBy('loc_cities.provDesc')
            ->orderBy('loc_cities.provDesc')
            ->get()
            ->toArray();

        // Cities with breeders or commodities
        $cities = \DB::table('loc_cities')
            ->join('breeders', 'loc_cities.id', '=', 'breeders.geolocation')
            ->join('commodities', 'breeders.id', '=', 'commodities.breeder_id')
            ->select('loc_cities.id as value', 'loc_cities.cityDesc as label')
            ->whereNotNull('loc_cities.cityDesc')
            ->groupBy('loc_cities.id', 'loc_cities.cityDesc')
            ->orderBy('loc_cities.cityDesc')
            ->get()
            ->toArray();

        return [
            'regions' => $regions,
            'provinces' => $provinces,
            'cities' => $cities,
        ];
    }
}
