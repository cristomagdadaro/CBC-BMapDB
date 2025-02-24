<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait OwnedByTrait {

    protected bool $ignoreUserBasedFiltration = true;

    protected bool $ignoreAffiliationBasedFiltration = false; // toggle

    public function scopeOwnedByUser(Builder $query, $user): Builder
    {
        $this->ignoreUserBasedFiltration =  auth()->check();

        if ($this->ignoreUserBasedFiltration || (auth()->check() && auth()->user()->isAdmin())) {
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
        $this->ignoreAffiliationBasedFiltration =  auth()->check();

        if ($this->ignoreAffiliationBasedFiltration || (auth()->check() && auth()->user()->isAdmin())) {
            return $query;
        }

        if (!auth()->check()) {
            return $query->whereRaw('1 = 0'); // Return no records if user is not authenticated
        }

        // Check if the affiliation column exists in the current table
        if (Schema::hasColumn($this->getTable(), 'affiliation')) {
            $query->orWhere('affiliation', auth()->user()->affiliation);
        }

        return $query;
    }

}
