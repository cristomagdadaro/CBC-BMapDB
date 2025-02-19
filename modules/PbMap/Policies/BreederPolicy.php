<?php

namespace Modules\PbMap\Policies;

use Modules\PbMap\Models\Breeder;
use Modules\PbMap\Enums\Permissions;
use App\Models\User;

class BreederPolicy
{
/*Route::middleware('can:'. Permission::READ_BREEDER->value)->get('/', [BreederController::class, 'index'])->name('api.breeders.index');
Route::middleware('can:'. Permission::READ_BREEDER->value)->get('/search/{id}', [BreederController::class, 'noPageSearch'])->name('api.breeders.noPageSearch');
Route::middleware('can:'. Permission::READ_BREEDER->value)->get('/summary/{parent_id?}/', [BreederController::class, 'summary'])->name('api.breeders.summary');
Route::middleware('can:'. Permission::READ_BREEDER->value)->get('/{id}', [BreederController::class, 'show'])->name('api.breeders.show');
Route::middleware('can:'. Permission::CREATE_BREEDER->value)->post('/', [BreederController::class, 'store'])->name('api.breeders.store');
Route::middleware('can:'. Permission::UPDATE_BREEDER->value)->put('/{id}', [BreederController::class, 'update'])->name('api.breeders.update');
Route::middleware('can:'. Permission::DELETE_BREEDER->value)->delete('/delete', [BreederController::class, 'multiDestroy'])->name('api.breeders.destroy.multi');
Route::middleware('can:'. Permission::DELETE_BREEDER->value)->delete('/{id}', [BreederController::class, 'destroy'])->name('api.breeders.destroy');*/
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Breeder $breeder): bool
    {
        return $user->hasPermissionTo($breeder) || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Breeder $breeder): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Breeder $breeder): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Breeder $breeder): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Breeder $breeder): bool
    {
        //
    }
}
