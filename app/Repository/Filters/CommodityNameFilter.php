<?php

namespace App\Repository\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Filters commodity results by exact commodity name when provided.
 */
class CommodityNameFilter extends AbstractFilter
{
    public function shouldApply(Collection $parameters): bool
    {
        return $this->hasParameter($parameters, 'commodity') || $this->hasParameter($parameters, 'commodities');
    }

    public function apply(Builder $query, Collection $parameters): Builder
    {
        $model = $query->getModel();
        if ($model->getTable() !== 'commodities') {
            return $query;
        }

        $commodity = $this->getParameter($parameters, 'commodity')
            ?? $this->getParameter($parameters, 'commodities');

        if (!$commodity) {
            return $query;
        }

        return $query->where('commodities.name', $commodity);
    }
}
