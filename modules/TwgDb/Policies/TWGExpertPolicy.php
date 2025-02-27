<?php

namespace Modules\TwgDb\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Modules\TwgDb\Enums\Permissions;
use Modules\TwgDb\Models\TWGExpert;

class TWGExpertPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::READ_TWG_EXPERT) || $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TWGExpert $tWGExpert): bool
    {
        return $user->hasPermissionTo(Permissions::READ_TWG_EXPERT) || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permissions::CREATE_TWG_EXPERT) || $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TWGExpert $tWGExpert): bool
    {
        return $user->hasPermissionTo(Permissions::UPDATE_TWG_EXPERT) || $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TWGExpert $tWGExpert): bool
    {
        return $user->hasPermissionTo(Permissions::DELETE_TWG_EXPERT) || $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TWGExpert $tWGExpert): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TWGExpert $tWGExpert): bool
    {
        return $user->isAdmin();
    }
}
