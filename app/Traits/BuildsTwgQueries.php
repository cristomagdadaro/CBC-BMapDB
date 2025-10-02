<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BuildsTwgQueries
{
    /**
     * Build the TWG summary query.
     *
     * @param Builder $query
     * @return Builder
     */
    protected function buildTwgSummaryQuery(Builder $query): Builder
    {
        return $query
            ->leftJoin('twg_expert', function ($join) {
                $join->on('users.id', '=', 'twg_expert.user_id')
                    ->whereNull('twg_expert.deleted_at');
            })
            ->leftJoin('twg_product', function ($join) {
                $join->on('twg_expert.institution', '=', 'twg_product.institution')
                    ->whereNull('twg_product.deleted_at');
            })
            ->leftJoin('twg_service', function ($join) {
                $join->on('twg_expert.institution', '=', 'twg_service.institution')
                    ->whereNull('twg_service.deleted_at');
            })
            ->leftJoin('twg_project', function ($join) {
                $join->on('twg_expert.institution', '=', 'twg_project.institution')
                    ->whereNull('twg_project.deleted_at');
            })
            ->leftJoin('institutes', function ($join) {
                $join->on('institutes.id', '=', 'users.affiliation')
                    ->whereNull('institutes.deleted_at');
            })
            ->groupBy('institutes.name')
            ->selectRaw('institutes.name as affiliation, COUNT(DISTINCT twg_expert.id) as experts, COUNT(DISTINCT twg_product.id) as products, COUNT(DISTINCT twg_project.id) as projects, COUNT(DISTINCT twg_service.id) as services');
    }
}

