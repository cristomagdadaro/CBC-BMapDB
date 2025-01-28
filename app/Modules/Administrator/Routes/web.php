<?php
namespace App\Modules\Administrator\Routes;

use App\Http\Middleware\AdminApprovedUser;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    AdminApprovedUser::class,
])->group(function () {

    Route::middleware('admin')->prefix('administrator')->group(function () {
        Route::get('/{any?}', function () {
            return Inertia::render('Admin/Administrator');
        })->name('administrator.index');

        Route::get('/users/{id}', function ($id) {
            return Inertia::render('Admin/components/NewUser/ViewUserAccount', [
                'view' => User::with(['accounts', 'roles', 'permissions'])->findOrFail($id),
                'breadcrumbs' => [['label' => 'Users', 'to' => '/administrator/users']],
            ]);
        })->name('administrator.user.view');
    });

});
