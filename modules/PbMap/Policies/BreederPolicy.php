<?php

namespace Modules\PbMap\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\PbMap\Models\Breeder;
use App\Models\User;

class BreederPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin()
            || $user->isFocalPerson()
            || $user->isBreeder()
            || $user->isResearcher();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Breeder $breeder): bool
    {
        return $this->viewAny($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isFocalPerson();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Breeder $breeder): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($this->isOrganizationLead($user) && $this->hasMatchingAffiliation($user, $breeder->affiliation)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Breeder $breeder): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($this->isOrganizationLead($user) && $this->hasMatchingAffiliation($user, $breeder->affiliation)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Breeder $breeder): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Breeder $breeder): bool
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
