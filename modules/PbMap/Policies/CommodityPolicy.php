<?php

namespace Modules\PbMap\Policies;

use Modules\PbMap\Enums\Permissions;
use Modules\PbMap\Models\Commodity;
use App\Models\User;

class CommodityPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::READ_COMMODITY) || $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Commodity $commodity): bool
    {
        return $user->hasPermissionTo(Permissions::READ_COMMODITY) || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::CREATE_COMMODITY);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Commodity $commodity): bool
    {
        return $user->hasPermissionTo(Permissions::UPDATE_COMMODITY) || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Commodity $commodity): bool
    {
        return $user->hasPermissionTo(Permissions::DELETE_COMMODITY) || $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Commodity $commodity): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Commodity $commodity): bool
    {
        return false;
    }
}
