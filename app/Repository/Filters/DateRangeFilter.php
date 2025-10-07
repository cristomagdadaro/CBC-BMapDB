<?php

namespace App\Repository\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Applies date range filtering.
 */
class DateRangeFilter extends AbstractFilter
{
    public function shouldApply(Collection $parameters): bool
    {
        return $this->hasParameter($parameters, 'date_from') ||
               $this->hasParameter($parameters, 'date_to') ||
               $this->hasParameter($parameters, 'date_column');
    }

    public function apply(Builder $query, Collection $parameters): Builder
    {
        $dateColumn = $this->getParameter($parameters, 'date_column', 'created_at');
        $dateFrom = $this->getParameter($parameters, 'date_from');
        $dateTo = $this->getParameter($parameters, 'date_to');

        if ($dateFrom) {
            $query->whereDate($dateColumn, '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate($dateColumn, '<=', $dateTo);
        }

        return $query;
    }
}


