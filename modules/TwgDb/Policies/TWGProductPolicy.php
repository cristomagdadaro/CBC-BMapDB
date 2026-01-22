<?php

namespace Modules\TwgDb\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Modules\TwgDb\Enums\Permissions;
use Modules\TwgDb\Models\TWGProduct;

class TWGProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isTwgManager();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TWGProduct $twgproduct): bool
    {
        return $this->viewAny($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isTwgManager();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TWGProduct $twgproduct): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isTwgManager()) {
            $userAff = (int) ($user->affiliation ?? 0);
            $modelAff = (int) ($twgproduct->institution ?? 0);
            return $userAff && $modelAff && $userAff === $modelAff;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TWGProduct $twgproduct): bool
    {
        return $this->update($user, $twgproduct);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TWGProduct $twgproduct): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TWGProduct $twgproduct): bool
    {
        return $user->isAdmin();
    }
}
