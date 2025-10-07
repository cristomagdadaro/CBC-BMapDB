<?php

namespace App\Repository\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

/**
 * Handles text search across model and related models.
 */
class SearchFilter extends AbstractFilter
{
    private const COL_FNAME = 'fname';
    private const COL_MNAME = 'mname';
    private const COL_LNAME = 'lname';
    private const COL_SUFFIX = 'suffix';
    private const COL_NAME = 'name';
    private const GEO_TABLE_USERS = 'users';

    private array $relations = [];

    public function shouldApply(Collection $parameters): bool
    {
        return $this->hasParameter($parameters, 'search');
    }

    public function apply(Builder $query, Collection $parameters): Builder
    {
        $searchTerm = $this->getParameter($parameters, 'search');
        $filter = $this->getParameter($parameters, 'filter');
        $isExact = $this->normalizeBoolean($this->getParameter($parameters, 'is_exact', false)) ?? false;

        // Get relations from 'with' parameter
        $with = $this->getParameter($parameters, 'with');
        if (is_string($with)) {
            $this->relations = explode(',', $with);
        }

        if (empty($searchTerm)) {
            return $query;
        }

        // Apply search on main model
        $this->applyMainModelSearch($query, $searchTerm, $filter, $isExact);

        // Apply search on related models
        $this->applyRelatedModelSearch($query, $searchTerm, $filter, $isExact);

        return $query;
    }

    /**
     * Apply search to the main model.
     */
    private function applyMainModelSearch(Builder $query, string $search, ?string $filter, bool $isExact): void
    {
        $model = $query->getModel();

        // Specific column filter
        if ($filter) {
            if (str_contains($filter, '.')) {
                $filter = explode('.', $filter)[1];
            }
            $operator = $isExact ? '=' : 'like';
            $value = $isExact ? $search : "%{$search}%";
            $query->where($filter, $operator, $value);
            return;
        }

        // Get searchable columns
        $columns = method_exists($model, 'getSearchable')
            ? collect($model->getSearchable())
            : collect([]);

        if ($columns->isEmpty()) {
            return;
        }

        // Handle full name search
        if ($this->hasNameColumns($columns)) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw("CONCAT_WS(' ', fname, mname, lname, suffix) LIKE ?", ["%{$search}%"]);
            });
            return;
        }

        // Search across all searchable columns
        $query->where(function ($subQuery) use ($columns, $search, $isExact) {
            foreach ($columns as $column) {
                $operator = $isExact ? '=' : 'like';
                $value = $isExact ? $search : "%{$search}%";
                $subQuery->orWhere($column, $operator, $value);
            }
        });
    }

    /**
     * Apply search to related models.
     */
    private function applyRelatedModelSearch(Builder $query, string $search, ?string $filter, bool $isExact): void
    {
        $model = $query->getModel();

        foreach ($this->relations as $relation) {
            if (!method_exists($model, $relation)) {
                continue;
            }

            $relatedModel = $model->{$relation}()->getModel();
            $this->applyRelationWhereHas($query, $relation, $relatedModel, $search, $filter, $isExact);
        }
    }

    /**
     * Apply whereHas for a specific relation.
     */
    private function applyRelationWhereHas(Builder $query, string $relation, $relatedModel, string $search, ?string $filter, bool $isExact): void
    {
        $query->orWhereHas($relation, function ($q) use ($relatedModel, $search, $filter, $isExact) {
            $table = $q->getModel()->getTable();
            $searchable = Schema::getColumnListing($table);

            // Parse filter if it contains a dot notation
            if ($filter && str_contains($filter, '.')) {
                $filter = explode('.', $filter)[1];
            }

            $q->where(function ($subQuery) use ($search, $searchable, $isExact, $table, $filter) {
                // Full name search for user tables or tables with name columns
                if ($this->shouldUseFullNameSearch($table, $searchable, $filter)) {
                    $subQuery->orWhereRaw("CONCAT_WS(' ', fname, mname, lname, suffix) LIKE ?", ["%{$search}%"]);
                } elseif ($filter && in_array($filter, $searchable, true)) {
                    // Specific filter column search
                    $operator = $isExact ? '=' : 'like';
                    $value = $isExact ? $search : "%{$search}%";
                    $subQuery->where($filter, $operator, $value);
                } else {
                    // Search across all searchable columns
                    foreach ($searchable as $column) {
                        if (Schema::hasColumn($table, $column)) {
                            $operator = $isExact ? '=' : 'like';
                            $value = $isExact ? $search : "%{$search}%";
                            $subQuery->orWhere($column, $operator, $value);
                        }
                    }
                }
            });
        });
    }

    /**
     * Check if the columns include name fields.
     */
    private function hasNameColumns(Collection $columns): bool
    {
        return $columns->contains(self::COL_FNAME) && $columns->contains(self::COL_LNAME);
    }

    /**
     * Determine if full name search should be used.
     */
    private function shouldUseFullNameSearch(string $table, array $searchable, ?string $filter): bool
    {
        return ($filter === self::COL_NAME &&
                in_array(self::COL_FNAME, $searchable, true) &&
                in_array(self::COL_LNAME, $searchable, true)) ||
               $table === self::GEO_TABLE_USERS;
    }
}

