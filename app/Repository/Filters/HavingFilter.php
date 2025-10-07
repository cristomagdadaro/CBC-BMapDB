<?php

namespace App\Repository\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Applies HAVING clause for filtering aggregated results.
 */
class HavingFilter extends AbstractFilter
{
    public function shouldApply(Collection $parameters): bool
    {
        return $this->hasParameter($parameters, 'having');
    }

    public function apply(Builder $query, Collection $parameters): Builder
    {
        $having = $this->getParameter($parameters, 'having');

        if (!$having) {
            return $query;
        }

        // Support array format: ['column' => 'value', 'operator' => '>', ...]
        if (is_array($having)) {
            $this->applyArrayHaving($query, $having);
            return $query;
        }

        // Support string format: "count(*) > 5"
        if (is_string($having)) {
            $query->havingRaw($having);
        }

        return $query;
    }

    /**
     * Apply HAVING clause from array format.
     */
    private function applyArrayHaving(Builder $query, array $having): void
    {
        $column = $having['column'] ?? null;
        $operator = $having['operator'] ?? '=';
        $value = $having['value'] ?? null;

        if ($column && $value !== null) {
            $query->having($column, $operator, $value);
        }
    }
}

