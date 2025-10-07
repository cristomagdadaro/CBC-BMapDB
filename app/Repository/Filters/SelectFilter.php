<?php

namespace App\Repository\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Applies custom SELECT columns including raw selects.
 */
class SelectFilter extends AbstractFilter
{
    public function shouldApply(Collection $parameters): bool
    {
        return $this->hasParameter($parameters, 'select_raw') ||
               $this->hasParameter($parameters, 'select');
    }

    public function apply(Builder $query, Collection $parameters): Builder
    {
        $selectRaw = $this->getParameter($parameters, 'select_raw');
        $select = $this->getParameter($parameters, 'select');

        if ($selectRaw) {
            $query->selectRaw($selectRaw);
            return $query;
        }

        if ($select) {
            $columns = is_string($select) ? explode(',', $select) : (array) $select;
            $columns = array_filter(array_map('trim', $columns));
            if (!empty($columns)) {
                $query->select($columns);
            }
            return $query;
        }

        // Apply role-based column selection for researchers viewing breeders
        $this->applyRoleBasedSelection($query);

        return $query;
    }

    /**
     * Apply role-based column selection restrictions.
     */
    private function applyRoleBasedSelection(Builder $query): void
    {
        try {
            if (!auth()->check()) {
                return;
            }

            $user = auth()->user();
            if (!method_exists($user, 'isResearcher') || !$user->isResearcher()) {
                return;
            }

            $table = $query->getModel()->getTable();
            if ($table === 'breeders') {
                $query->select([
                    'breeders.id',
                    'breeders.fname',
                    'breeders.mname',
                    'breeders.lname',
                    'breeders.suffix',
                    'breeders.affiliation',
                    'breeders.geolocation',
                    'breeders.breeder_type',
                    'breeders.created_at',
                    'breeders.updated_at',
                ]);
            }
        } catch (\Throwable $e) {
            // Silently fail and proceed with default selection
        }
    }
}

