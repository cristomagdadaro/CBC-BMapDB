<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class Filter extends Builder
{
    protected string $searchTerm = '';
    protected ?string $filterColumn = null;
    protected bool $isExact = false;

    public function __construct($query)
    {
        parent::__construct($query);
    }

    /**
     * @param string $searchTerm
     */
    public function setSearchTerm(string $searchTerm): self
    {
        $this->searchTerm = $searchTerm;
        return $this->query();
    }

    /**
     * Apply specific filtering logic for each subclass
     */
    abstract protected function applyFilter(): void;

    /**
     * Apply search and filter conditions to the query
     *
     * @param Builder $query
     */
    protected function applySearch(Builder $query): void
    {
        parent::applySearch($query);

        foreach ($this->getSearchableColumns() as $column) {
            if ($this->searchTerm && !is_null($this->filterColumn)) {
                $this->addFilterCondition($column, $this->searchTerm);
            }
        }
    }

    /**
     * Get columns that can be filtered
     */
    protected abstract function getSearchableColumns(): array;

    /**
     * Add a filter condition for the column
     *
     * @param string $column The column name
     * @param mixed   $value   The value to compare against
     */
    protected function addFilterCondition($column, $value): void
    {
        if ($this->isExact) {
            $this->where($column, $value);
        } else {
            $this->orWhere($column, $value);
        }
    }

    /**
     * Apply relationship and count handling
     */
    public function handleRelationships(): void
    {
        foreach ($this->appendWith as $relation) {
            if (method_exists($this->model, $relation)) {
                $relatedModel = $this->model->{$relation}();
                $this->applyRelationSearch($relatedModel);
            }
        }

        // Handle counts for each relation here if needed
    }

    /**
     * Apply relationship search
     *
     * @param Model $relatedModel The related model
     */
    protected function applyRelationSearch(Model $relatedModel): void
    {
        // Implement specific logic for applying filters to relationships
        parent::applyRelationSearch($relatedModel);
    }
}
