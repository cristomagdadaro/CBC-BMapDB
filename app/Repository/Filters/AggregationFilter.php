<?php

namespace App\Repository\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Applies aggregation functions like COUNT, SUM, AVG, etc.
 * Supports both simple aggregations and grouped aggregations.
 */
class AggregationFilter extends AbstractFilter
{
    private const VALID_FUNCTIONS = ['count', 'sum', 'avg', 'max', 'min'];

    public function shouldApply(Collection $parameters): bool
    {
        return $this->hasParameter($parameters, 'aggregate') ||
            $this->hasParameter($parameters, 'aggregate_function');
    }

    public function apply(Builder $query, Collection $parameters): Builder
    {
        $aggregateFunction = $this->getParameter($parameters, 'aggregate_function');
        $aggregateColumn = $this->getParameter($parameters, 'aggregate_column', '*');
        $aggregateAlias = $this->getParameter($parameters, 'aggregate_alias');

        // Support shorthand 'aggregate' parameter: "count:column_name as alias"
        $aggregate = $this->getParameter($parameters, 'aggregate');
        if ($aggregate) {
            $this->parseAggregateShorthand($query, $aggregate);
            return $query;
        }

        // Standard aggregation
        if ($aggregateFunction && $this->isValidFunction($aggregateFunction)) {
            $this->applyAggregation($query, $aggregateFunction, $aggregateColumn, $aggregateAlias);
        }

        return $query;
    }

    /**
     * Parse shorthand aggregation syntax.
     * Example: "count:id as total", "sum:amount as total_amount"
     */
    private function parseAggregateShorthand(Builder $query, string $aggregate): void
    {
        // Pattern: function:column as alias
        if (preg_match('/^(\w+):(\w+|\*)\s+as\s+(\w+)$/i', $aggregate, $matches)) {
            $function = strtolower($matches[1]);
            $column = $matches[2];
            $alias = $matches[3];

            if ($this->isValidFunction($function)) {
                $this->applyAggregation($query, $function, $column, $alias);
            }
        }
        // Pattern: function:column (no alias)
        elseif (preg_match('/^(\w+):(\w+|\*)$/i', $aggregate, $matches)) {
            $function = strtolower($matches[1]);
            $column = $matches[2];

            if ($this->isValidFunction($function)) {
                $this->applyAggregation($query, $function, $column);
            }
        }
    }

    /**
     * Apply the aggregation to the query.
     */
    private function applyAggregation(Builder $query, string $function, string $column, ?string $alias = null): void
    {
        $aggregateExpr = strtoupper($function) . "({$column})";

        if ($alias) {
            $query->selectRaw("{$aggregateExpr} as {$alias}");
        } else {
            $query->selectRaw($aggregateExpr);
        }
    }

    /**
     * Validate aggregation function.
     */
    private function isValidFunction(string $function): bool
    {
        return in_array(strtolower($function), self::VALID_FUNCTIONS, true);
    }
}
