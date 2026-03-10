<?php

namespace App\Repository\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Manages the filter pipeline, applying all filters in the correct order.
 */
class FilterPipeline
{
    /**
     * @var FilterContract[]
     */
    private array $filters = [];

    /**
     * Register a filter to the pipeline.
     *
     * @param FilterContract $filter
     * @return $this
     */
    public function addFilter(FilterContract $filter): self
    {
        $this->filters[] = $filter;
        return $this;
    }

    /**
     * Apply all filters to the query builder.
     *
     * @param Builder $query
     * @param Collection $parameters
     * @return Builder
     */
    public function apply(Builder $query, Collection $parameters): Builder
    {
        foreach ($this->filters as $filter) {
            if ($filter->shouldApply($parameters)) {
                $query = $filter->apply($query, $parameters);
            }
        }

        return $query;
    }

    /**
     * Create a default filter pipeline with standard filters in optimal order.
     *
     * @return self
     */
    public static function createDefault(): self
    {
        $pipeline = new self();

        // Order matters: apply filters in the most efficient sequence
        return $pipeline
            ->addFilter(new SelectFilter())           // 1. Select columns first
            ->addFilter(new RelationshipFilter())     // 2. Define relationships
            ->addFilter(new ParentFilter())           // 3. Parent filtering (most restrictive)
            ->addFilter(new GeoLocationFilter())      // 4. Geographic filtering (joins + filters)
            ->addFilter(new CommodityNameFilter())    // 5. Commodity name filtering (commodities only)
            ->addFilter(new SearchFilter())           // 5. Text search
            ->addFilter(new GroupByFilter())          // 6. Aggregation grouping
            ->addFilter(new SortFilter());            // 7. Sort last (after all filtering)
    }

    /**
     * Clear all filters from the pipeline.
     *
     * @return $this
     */
    public function clear(): self
    {
        $this->filters = [];
        return $this;
    }

    /**
     * Get all registered filters.
     *
     * @return FilterContract[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }
}
