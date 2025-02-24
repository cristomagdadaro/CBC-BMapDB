<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait OwnedByTrait {

    protected bool $ignoreUserBasedFiltration = false;

    protected bool $ignoreAffiliationBasedFiltration = false; // toggle

    public function scopeOwnedByUser(Builder $query, $user): Builder
    {
        if ($this->ignoreUserBasedFiltration || auth()->user()->isAdmin()) {
            return $query;
        }

        if (!auth()->check()) {
            return $query->whereRaw('1 = 0');
        }

        if (Schema::hasColumn($this->getTable(), 'user_id')) {
            $query->where('user_id', auth()->id());
        }

        return $query;
    }

    public function scopeOwnedByAffiliation(Builder $query, $user): Builder
    {
        if ($this->ignoreAffiliationBasedFiltration || auth()->user()->isAdmin()) {
            return $query;
        }

        if (!auth()->check()) {
            return $query->whereRaw('1 = 0'); // Return no records if user is not authenticated
        }

        // Check if the affiliation column exists before applying the filter
        if (Schema::hasColumn($this->getTable(), 'affiliation')) {
            $query->orWhere('affiliation', auth()->user()->affiliation);
        }

        return $query;
    }

}
