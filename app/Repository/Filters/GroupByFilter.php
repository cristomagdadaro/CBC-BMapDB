<?php

namespace App\Repository\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Applies GROUP BY clause to queries.
 */
class GroupByFilter extends AbstractFilter
{
    public function shouldApply(Collection $parameters): bool
    {
        return $this->hasParameter($parameters, 'group_by');
    }

    public function apply(Builder $query, Collection $parameters): Builder
    {
        $groupBy = $this->getParameter($parameters, 'group_by');

        if (is_string($groupBy)) {
            $query->groupBy($groupBy);
        } elseif (is_array($groupBy)) {
            $query->groupBy($groupBy);
        }

        return $query;
    }
}

