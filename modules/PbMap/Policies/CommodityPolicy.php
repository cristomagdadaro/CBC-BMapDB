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
     * Breeders: can only update their own commodities; Admins: always allowed.
     */
    public function update(User $user, Commodity $commodity): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if (!$user->hasPermissionTo(Permissions::UPDATE_COMMODITY)) {
            return false;
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

        // For other roles with update permission, allow
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     * Breeders: can only delete their own commodities; Admins: always allowed.
     */
    public function delete(User $user, Commodity $commodity): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if (!$user->hasPermissionTo(Permissions::DELETE_COMMODITY)) {
            return false;
        }

        if ($user->isBreeder()) {
            $ownsDirectly = (int) $commodity->user_id === (int) $user->id;
            if ($ownsDirectly) return true;

            $breederUserId = $commodity->relationLoaded('breeder')
                ? optional($commodity->breeder)->user_id
                : $commodity->breeder()->value('user_id');

            return (int) $breederUserId === (int) $user->id;
        }

        return true;
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
}
