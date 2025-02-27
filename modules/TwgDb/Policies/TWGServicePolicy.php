<?php

namespace Modules\TwgDb\Policies;

use Modules\TwgDb\Enums\Permissions;
use Modules\TwgDb\Models\TWGService;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TWGServicePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::READ_TWG_SERVICE) || $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TWGService $tWGService): bool
    {
        return $user->hasPermissionTo(Permissions::READ_TWG_SERVICE) || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::CREATE_TWG_SERVICE) || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TWGService $tWGService): bool
    {
        return $user->hasPermissionTo(Permissions::UPDATE_TWG_SERVICE) || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TWGService $tWGService): bool
    {
        return $user->hasPermissionTo(Permissions::DELETE_TWG_SERVICE) || $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TWGService $tWGService): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TWGService $tWGService): bool
    {
        return $user->isAdmin();
    }
}
