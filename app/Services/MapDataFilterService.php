<?php

namespace App\Services;

use App\Services\Filters\BaseFilterStrategy;
use App\Services\Filters\CommodityFilterStrategy;
use App\Services\Filters\BreederFilterStrategy;
use App\Services\Filters\InstituteFilterStrategy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Centralized service for handling map data filtering and aggregation
 * Provides a clean, maintainable approach to data filtration
 */
class MapDataFilterService
{
    private array $filterStrategies = [];

    public function __construct()
    {
        $this->registerFilterStrategies();
    }

    /**
     * Register available filter strategies
     */
    private function registerFilterStrategies(): void
    {
        $this->filterStrategies = [
            'commodities' => new CommodityFilterStrategy(),
            'breeders' => new BreederFilterStrategy(),
            'institutes' => new InstituteFilterStrategy(),
        ];
    }

    /**
     * Get filtered and aggregated data for map plotting
     */
    public function getMapData(string $dataType, array $filters = []): array
    {
        $strategy = $this->getFilterStrategy($dataType);

        if (!$strategy) {
            throw new \InvalidArgumentException("Unsupported data type: {$dataType}");
        }

        $normalizedFilters = $this->normalizeFilters($filters);

        $query = $strategy->buildBaseQuery();
        $query = $strategy->applyFilters($query, $normalizedFilters);
        $aggregatedData = $strategy->aggregateData($query, $normalizedFilters);

        return [
            'sql' => $query->toSql(),
            'data' => $aggregatedData,
            'metadata' => $this->generateMetadata($dataType, $normalizedFilters),
            'options' => $this->getFilterOptions($dataType, $normalizedFilters),
        ];
    }

    /**
     * Get available filter options for a data type
     */
    public function getFilterOptions(string $dataType, array $currentFilters = []): array
    {
        $strategy = $this->getFilterStrategy($dataType);

        if (!$strategy) {
            return [];
        }

        return $strategy->getAvailableOptions($currentFilters);
    }

    /**
     * Get aggregated summary data
     */
    public function getSummaryData(string $dataType, array $filters = []): array
    {
        $strategy = $this->getFilterStrategy($dataType);

        if (!$strategy) {
            return [];
        }

        $normalizedFilters = $this->normalizeFilters($filters);

        $query = $strategy->buildBaseQuery();
        $query = $strategy->applyFilters($query, $normalizedFilters);

        return $strategy->getSummaryStats($query);
    }

    /**
     * Get geographic distribution data
     */
    public function getGeographicDistribution(string $dataType, string $groupBy = 'region', array $filters = []): array
    {
        $strategy = $this->getFilterStrategy($dataType);

        if (!$strategy) {
            return [];
        }

        $normalizedFilters = $this->normalizeFilters($filters);

        $query = $strategy->buildBaseQuery();
        $query = $strategy->applyFilters($query, $normalizedFilters);

        return $strategy->getGeographicDistribution($query, $groupBy);
    }

    /**
     * Get the appropriate filter strategy
     */
    private function getFilterStrategy(string $dataType): ?BaseFilterStrategy
    {
        return $this->filterStrategies[$dataType] ?? null;
    }

    /**
     * Generate metadata for the filtered data
     */
    private function generateMetadata(string $dataType, array $filters): array
    {
        return [
            'data_type' => $dataType,
            'filters_applied' => $filters,
            'generated_at' => now()->toISOString(),
            'total_filters' => count(array_filter($filters)),
        ];
    }

    /**
     * Validate filter parameters
     */
    public function validateFilters(string $dataType, array $filters): array
    {
        $strategy = $this->getFilterStrategy($dataType);

        if (!$strategy) {
            return ['valid' => false, 'errors' => ['Invalid data type']];
        }

        $normalizedFilters = $this->normalizeFilters($filters);

        return $strategy->validateFilters($normalizedFilters);
    }

    /**
     * Normalize filter keys to align with standard backend schema.
     */
    private function normalizeFilters(array $filters): array
    {
        $normalized = $filters;

        if (isset($normalized['regions']) && !isset($normalized['region'])) {
            $normalized['region'] = $normalized['regions'];
        }

        if (isset($normalized['provinces']) && !isset($normalized['province'])) {
            $normalized['province'] = $normalized['provinces'];
        }

        if (isset($normalized['cities']) && !isset($normalized['city'])) {
            $normalized['city'] = $normalized['cities'];
        }

        if (isset($normalized['commodities']) && !isset($normalized['commodity'])) {
            $normalized['commodity'] = $normalized['commodities'];
        }

        if (!empty($normalized['geo_location_filter']) && array_key_exists('geo_location_value', $normalized)) {
            $keyMap = [
                'region' => 'region',
                'province' => 'province',
                'city' => 'city',
                'institute' => 'institute',
                'affiliation' => 'institute',
            ];

            $filterKey = $normalized['geo_location_filter'];
            if (isset($keyMap[$filterKey]) && $normalized['geo_location_value'] !== null) {
                $normalized[$keyMap[$filterKey]] = $normalized['geo_location_value'];
            }
        }

        return $normalized;
    }
}
