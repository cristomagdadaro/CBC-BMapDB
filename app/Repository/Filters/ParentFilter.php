<?php

namespace App\Repository\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Filters results by parent relationship.
 */
class ParentFilter extends AbstractFilter
{
    public function shouldApply(Collection $parameters): bool
    {
        return $this->hasParameter($parameters, 'filter_by_parent_column') &&
               $this->hasParameter($parameters, 'filter_by_parent_id');
    }

    public function apply(Builder $query, Collection $parameters): Builder
    {
        $column = $this->getParameter($parameters, 'filter_by_parent_column');
        $id = $this->getParameter($parameters, 'filter_by_parent_id');

        if ($column && $id) {
            $query->where($column, $id);
        }

        return $query;
    }
}

