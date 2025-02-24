<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\Accounts;
use App\Models\User;

class AccountPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Accounts $account): bool
    {
        return $user->hasPermissionTo(Permission::READ_APP_ACCOUNT) || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CREATE_APP_ACCOUNT);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Accounts $account): bool
    {
        return $user->hasPermissionTo(Permission::UPDATE_APP_ACCOUNT) || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Accounts $account): bool
    {
        return $user->hasPermissionTo(Permission::DELETE_APP_ACCOUNT) || $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Accounts $account): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Accounts $account): bool
    {
        return false;
    }
}
