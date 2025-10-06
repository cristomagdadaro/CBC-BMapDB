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

        $query = $strategy->buildBaseQuery();
        $query = $strategy->applyFilters($query, $filters);
        $aggregatedData = $strategy->aggregateData($query, $filters);

        return [
            'sql' => $query->toSql(),
            'data' => $aggregatedData,
            'metadata' => $this->generateMetadata($dataType, $filters),
            'options' => $this->getFilterOptions($dataType, $filters),
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

        $query = $strategy->buildBaseQuery();
        $query = $strategy->applyFilters($query, $filters);

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

        $query = $strategy->buildBaseQuery();
        $query = $strategy->applyFilters($query, $filters);

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

        return $strategy->validateFilters($filters);
    }
}
