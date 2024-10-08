<?php

/*System Related APIs*/

use App\Enums\Permission;
use App\Http\Controllers\API\AccountController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\ApplicationController;
use App\Http\Controllers\API\InstituteController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*Admin Related APIs*/
Route::middleware('admin')->prefix('administrator')->group(function () {
    Route::middleware(['can:'. Permission::READ_USER->value])->get('/', [AdminController::class, 'index'])->name('api.administrator.index');
    Route::middleware(['can:'. Permission::READ_USER->value])->get('/{id}', [AdminController::class, 'show'])->name('api.administrator.show');
    Route::middleware(['can:'. Permission::CREATE_USER->value])->post('/', [AdminController::class, 'store'])->name('api.administrator.store');
    Route::middleware(['can:'. Permission::UPDATE_USER->value])->put('/{id}', [AdminController::class, 'update'])->name('api.administrator.update');
    Route::middleware(['can:'. Permission::DELETE_USER->value])->delete('/{id}', [AdminController::class, 'destroy'])->name('api.administrator.destroy');
    Route::middleware(['can:'. Permission::DELETE_USER->value])->delete('/delete', [AdminController::class, 'multiDestroy'])->name('api.administrator.destroy.multi');
});

Route::prefix('roles')->group(function () {
    Route::get('/', [RoleController::class, 'index'])->name('api.roles.index');
    Route::get('/{id}', [RoleController::class, 'show'])->name('api.roles.show');
    Route::post('/', [RoleController::class, 'store'])->name('api.roles.store');
    Route::put('/{id}', [RoleController::class, 'update'])->name('api.roles.update');
    Route::delete('/{id}', [RoleController::class, 'destroy'])->name('api.roles.destroy');
});

Route::prefix('permissions')->group(function () {
    Route::get('/', [PermissionController::class, 'index'])->name('api.permissions.index');
    Route::get('/{id}', [PermissionController::class, 'show'])->name('api.permissions.show');
    Route::post('/', [PermissionController::class, 'store'])->name('api.permissions.store');
    Route::put('/{id}', [PermissionController::class, 'update'])->name('api.permissions.update');
    Route::delete('/{id}', [PermissionController::class, 'destroy'])->name('api.permissions.destroy');
});

Route::prefix('applications')->group(function () {
    Route::middleware(['can:'. Permission::READ_APP->value])->get('/', [ApplicationController::class, 'index'])->name('api.applications.index');
    Route::middleware(['can:'. Permission::READ_APP->value])->get('/{id}', [ApplicationController::class, 'show'])->name('api.applications.show');
    Route::middleware(['can:'. Permission::CREATE_APP->value])->post('/', [ApplicationController::class, 'store'])->name('api.applications.store');
    Route::middleware(['can:'. Permission::UPDATE_APP->value])->put('/{id}', [ApplicationController::class, 'update'])->name('api.applications.update');
    Route::middleware(['can:'. Permission::DELETE_APP->value])->delete('/{id}', [ApplicationController::class, 'destroy'])->name('api.applications.destroy');
});

Route::prefix('users')->group(function () {
    Route::middleware(['can:'. Permission::READ_USER->value])->get('/', [UserController::class, 'index'])->name('api.users.index');
    Route::middleware(['can:'. Permission::READ_USER->value])->get('/{id}', [UserController::class, 'show'])->name('api.users.show');
});

Route::prefix('institutes')->group(function () {
    Route::get('/', [InstituteController::class, 'index'])->name('api.institutes.index');
    Route::get('/{id}', [InstituteController::class, 'show'])->name('api.institutes.show');
});

Route::prefix('accounts')->group(function () {
    Route::middleware(['can:'. Permission::READ_APP_ACCOUNT->value])->get('/', [AccountController::class, 'index'])->name('api.accounts.index');
    Route::middleware(['can:'. Permission::READ_APP_ACCOUNT->value])->get('/{id}', [AccountController::class, 'show'])->name('api.accounts.show');
    Route::middleware(['can:'. Permission::CREATE_APP_ACCOUNT->value])->post('/', [AccountController::class, 'store'])->name('api.accounts.store');
    Route::middleware(['can:'. Permission::UPDATE_APP_ACCOUNT->value])->put('/{id}', [AccountController::class, 'update'])->name('api.accounts.update');
    Route::middleware(['can:'. Permission::DELETE_APP_ACCOUNT->value])->delete('/{id}', [AccountController::class, 'destroy'])->name('api.accounts.destroy');
});
