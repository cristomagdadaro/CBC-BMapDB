<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait OwnedByTrait {
    public function scopeOwnedBy(Builder $query, $user)
    {
        if ($this->ignoreUserBasedFiltration)
            return $query;

        if (!auth()->check()) {
            return $query->whereRaw('1 = 0'); // Return no records if user is not authenticated
        }

        // Check if the user is an admin and allow all data
        if (auth()->user()->isAdmin()) {
            return $query;
        }

        // Apply ownership filter based on user's ID or affiliation
        $query->where('user_id', auth()->id())
            ->orWhere('affiliation', auth()->user()->affiliation);

        return $query;
    }
}
