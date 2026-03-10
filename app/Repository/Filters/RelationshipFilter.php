<?php

namespace App\Repository\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Handles eager loading of relationships and relationship counts.
 */
class RelationshipFilter extends AbstractFilter
{
    public function shouldApply(Collection $parameters): bool
    {
        return $this->hasParameter($parameters, 'with') ||
               $this->hasParameter($parameters, 'count');
    }

    public function apply(Builder $query, Collection $parameters): Builder
    {
        $with = $this->getParameter($parameters, 'with');
        $count = $this->getParameter($parameters, 'count');

        // Apply eager loading
        if ($with) {
            $relations = is_string($with) ? explode(',', $with) : (array) $with;
            $relations = array_filter(array_map('trim', $relations));
            if (!empty($relations)) {
                $query->with($relations);
            }
        }

        // Apply relationship counts
        if ($count) {
            $countRelations = is_string($count) ? explode(',', $count) : (array) $count;
            $countRelations = array_filter(array_map('trim', $countRelations));
            if (!empty($countRelations)) {
                $query->withCount($countRelations);
            }
        }

        return $query;
    }
}

