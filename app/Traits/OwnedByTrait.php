<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait OwnedByTrait {

    /**
     * Apply user-based ownership scoping.
     * Breeder -> only their own records, except on the breeders table where we defer to affiliation.
     * Admin, Focal Person, Researcher -> no user-based restriction.
     */
    public function scopeOwnedByUser(Builder $query, ?User $user = null): Builder
    {
        $authUser = auth()->user();
        $currentUser = $user instanceof User ? $user : ($authUser instanceof User ? $authUser : null);

        if (!$currentUser) {
            return $query->whereRaw('1 = 0');
        }

        /** @var User $currentUser */

        // Resolve model/table once
        $model = $query->getModel();
        $table = $model->getTable();

        // Opt-out: allow a model to bypass user-based filtration if it sets this property
        if (property_exists($model, 'ignoreUserBasedFiltration') && $model->ignoreUserBasedFiltration === true) {
            return $query;
        }

        // Admin, Focal Person, Researcher: no user-based restriction
        if ($currentUser->isAdmin() || $currentUser->hasRole(\App\Enums\Role::FOCAL_PERSON->value) || $currentUser->hasRole(\App\Enums\Role::RESEARCHER->value)) {
            return $query;
        }

        // Let the model bypass user-based filtration if it implements this logic (e.g. for breeders/commodities)
        if (method_exists($model, 'bypassUserBasedOwnership') && $model->bypassUserBasedOwnership($currentUser)) {
            return $query;
        }

        // Otherwise (e.g., other tables): restrict to own records when user_id exists on table
        $hasUserId = Schema::hasColumn($table, 'user_id');
        if ($hasUserId) {
            $userIdCol = method_exists($model, 'qualifyColumn') ? $model->qualifyColumn('user_id') : ($table . '.user_id');
            $query->where($userIdCol, $currentUser->id);
        }

        return $query;
    }

    /**
     * Apply affiliation-based scoping.
     * Focal Person -> only within their institute.
     * Breeder -> for breeders table, show other breeders within the same institute.
     * Admin, Researcher -> no affiliation restriction.
     */
    public function scopeOwnedByAffiliation(Builder $query, ?User $user = null): Builder
    {
        $authUser = auth()->user();
        $currentUser = $user instanceof User ? $user : ($authUser instanceof User ? $authUser : null);

        if (!$currentUser) {
            return $query->whereRaw('1 = 0');
        }

        /** @var User $currentUser */

        // Resolve table and common column presence once
        $model = $query->getModel();
        $table = $model->getTable();

        // Opt-out: allow a model to bypass affiliation-based filtration if it sets this property
        if (property_exists($model, 'ignoreAffiliationBasedFiltration') && $model->ignoreAffiliationBasedFiltration === true) {
            return $query;
        }

        $hasAffiliation = Schema::hasColumn($table, 'affiliation');
        $hasInstitution = Schema::hasColumn($table, 'institution');
        $hasUserId = Schema::hasColumn($table, 'user_id');

        // Precompute qualified columns for safety with aliases
        $affiliationCol = method_exists($model, 'qualifyColumn') ? $model->qualifyColumn('affiliation') : ($table . '.affiliation');
        $institutionCol = method_exists($model, 'qualifyColumn') ? $model->qualifyColumn('institution') : ($table . '.institution');
        $userIdCol = method_exists($model, 'qualifyColumn') ? $model->qualifyColumn('user_id') : ($table . '.user_id');

        // Let the model apply custom affiliation-based filtration (e.g. for breeders/commodities viewed by a breeder)
        if (method_exists($model, 'applyCustomAffiliationOwnership')) {
            $applied = $model->applyCustomAffiliationOwnership($query, $currentUser, $affiliationCol, $userIdCol);
            if ($applied) {
                return $query;
            }
        }

        // Only focal persons are restricted by affiliation for other models
        if (!$currentUser->hasRole(\App\Enums\Role::FOCAL_PERSON->value)) {
            return $query;
        }

        // Guard: focal person with empty affiliation should see no records
        $aff = trim((string) $currentUser->affiliation);
        if ($aff === '') {
            return $query->whereRaw('1 = 0');
        }

        // If model has direct affiliation column, filter on it
        if ($hasAffiliation) {
            return $query->where($affiliationCol, $aff);
        }

        // If model uses 'institution' column (TWG tables), filter on it
        if ($hasInstitution) {
            return $query->where($institutionCol, $aff);
        }

        // Otherwise, attempt to scope via known relations that contain affiliation
        // IMPORTANT: Use withoutGlobalScopes() to prevent infinite recursion
        if (method_exists($model, 'breeder')) {
            return $query->whereHas('breeder', function (Builder $q) use ($aff) {
                $q->withoutGlobalScopes()->where('affiliation', $aff);
            });
        }

        if (method_exists($model, 'user')) {
            return $query->whereHas('user', function (Builder $q) use ($aff) {
                $q->withoutGlobalScopes()->where('affiliation', $aff);
            });
        }

        return $query;
    }

}
