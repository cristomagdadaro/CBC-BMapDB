<?php

namespace Modules\PbMap\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\PbMap\Enums\Permissions;
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
        return $user->hasPermissionTo(Permissions::READ_BREEDER) || $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Breeder $breeder): bool
    {
        return $user->hasPermissionTo(Permissions::READ_BREEDER) || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::CREATE_BREEDER);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Breeder $breeder): bool
    {
        return $user->hasPermissionTo(Permissions::UPDATE_BREEDER) || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Breeder $breeder): bool
    {
        return $user->hasPermissionTo(Permissions::DELETE_BREEDER) || $user->isAdmin();
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
}
