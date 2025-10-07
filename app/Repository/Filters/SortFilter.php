<?php

namespace App\Repository\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

/**
 * Handles query result sorting with validation.
 */
class SortFilter extends AbstractFilter
{
    private const COL_ID = 'id';
    private const COL_UUID = 'uuid';
    private const ORDER_ASC = 'ASC';
    private const ORDER_DESC = 'DESC';
    private const DEFAULT_ORDER = 'desc';

    public function shouldApply(Collection $parameters): bool
    {
        return $this->hasParameter($parameters, 'sort');
    }

    public function apply(Builder $query, Collection $parameters): Builder
    {
        $sortColumn = $this->getParameter($parameters, 'sort');
        $order = strtoupper($this->getParameter($parameters, 'order', self::DEFAULT_ORDER));

        if (!$sortColumn || !is_string($sortColumn)) {
            return $query;
        }

        // Validate order direction
        if (!in_array($order, [self::ORDER_ASC, self::ORDER_DESC], true)) {
            $order = self::ORDER_DESC;
        }

        // Validate and resolve sort column
        $sortColumn = $this->resolveSortColumn($query, $sortColumn);

        $query->orderBy($sortColumn, $order);

        return $query;
    }

    /**
     * Resolve and validate the sort column.
     */
    private function resolveSortColumn(Builder $query, string $sortColumn): string
    {
        $model = $query->getModel();
        $table = $model->getTable();

        // If column exists on the table, use it
        if (Schema::hasColumn($table, $sortColumn)) {
            return $sortColumn;
        }

        // Check if it's in a SELECT RAW clause
        $selectedColumns = $query->getQuery()->columns;
        if ($selectedColumns) {
            $selectedRaw = is_array($selectedColumns) ? $selectedColumns[0] : $selectedColumns;
            if (is_string($selectedRaw) && str_contains($selectedRaw, $sortColumn)) {
                return $sortColumn;
            }
        }

        // Fallback to ID or UUID
        if (Schema::hasColumn($table, self::COL_ID)) {
            return $table . '.' . self::COL_ID;
        }

        return self::COL_UUID;
    }
}

