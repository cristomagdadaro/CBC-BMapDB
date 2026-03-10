<?php

namespace App\Repository\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Contract for all filter implementations.
 * Each filter is responsible for applying a specific type of filtering logic.
 */
interface FilterContract
{
    /**
     * Apply the filter to the query builder.
     *
     * @param Builder $query
     * @param Collection $parameters
     * @return Builder
     */
    public function apply(Builder $query, Collection $parameters): Builder;

    /**
     * Determine if this filter should be applied based on parameters.
     *
     * @param Collection $parameters
     * @return bool
     */
    public function shouldApply(Collection $parameters): bool;
}
