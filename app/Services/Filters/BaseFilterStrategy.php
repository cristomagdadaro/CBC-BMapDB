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
     */
    protected function getGeographicOptions(): array
    {
        return [
            'regions' => \DB::table('loc_cities')
                ->select('regDesc as value', 'regDesc as label')
                ->distinct()
                ->orderBy('regDesc')
                ->get()
                ->toArray(),
            'provinces' => \DB::table('loc_cities')
                ->select('provDesc as value', 'provDesc as label')
                ->distinct()
                ->orderBy('provDesc')
                ->get()
                ->toArray(),
            'cities' => \DB::table('loc_cities')
                ->select('cityDesc as value', 'cityDesc as label')
                ->distinct()
                ->orderBy('cityDesc')
                ->get()
                ->toArray(),
        ];
    }
}
