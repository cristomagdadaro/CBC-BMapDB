<?php

namespace Modules\PbMap\Policies;

use Modules\PbMap\Models\Commodity;
use App\Models\User;

class CommodityPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin()
            || $user->isResearcher()
            || $user->isBreeder()
            || $user->isFocalPerson();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Commodity $commodity): bool
    {
        return $this->viewAny($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin()
            || $user->isFocalPerson()
            || $user->isBreeder();
    }

    /**
     * Determine whether the user can update the model.
     * Breeders: can only update their own commodities; Admins: always allowed; Focal Person: can update commodities within their institute.
     */
    public function update(User $user, Commodity $commodity): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($this->isOrganizationLead($user)) {
            $commodityAff = (int) ($commodity->relationLoaded('breeder') ? optional($commodity->breeder)->affiliation : $commodity->breeder()->value('affiliation'));
            if ($this->hasMatchingAffiliation($user, $commodityAff)) {
                return true;
            }
        }

        // If the acting user is a breeder, restrict to own records
        if ($user->isBreeder()) {
            // Prefer direct user_id on the commodity; fallback to breeder relation's user_id
            $ownsDirectly = (int) $commodity->user_id === (int) $user->id;
            if ($ownsDirectly) return true;

            $breederUserId = $commodity->relationLoaded('breeder')
                ? optional($commodity->breeder)->user_id
                : $commodity->breeder()->value('user_id');

            return (int) $breederUserId === (int) $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     * Breeders: can only delete their own commodities; Admins: always allowed; Focal Person: can delete commodities within their institute.
     */
    public function delete(User $user, Commodity $commodity): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($this->isOrganizationLead($user)) {
            $commodityAff = (int) ($commodity->relationLoaded('breeder') ? optional($commodity->breeder)->affiliation : $commodity->breeder()->value('affiliation'));
            if ($this->hasMatchingAffiliation($user, $commodityAff)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Commodity $commodity): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Commodity $commodity): bool
    {
        return $user->isAdmin();
    }

    private function isOrganizationLead(User $user): bool
    {
        return $user->isFocalPerson() || $user->isTwgManager();
    }

    private function hasMatchingAffiliation(User $user, ?int $affiliation): bool
    {
        $userAff = (int) ($user->affiliation ?? 0);
        $entityAff = (int) ($affiliation ?? 0);

        return $userAff && $entityAff && $userAff === $entityAff;
    }
}
