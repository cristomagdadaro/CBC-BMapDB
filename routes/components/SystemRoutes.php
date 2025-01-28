<?php

/*System Related APIs*/

use App\Enums\Permission;
use App\Http\Controllers\API\InstituteController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('users')->group(function () {
    Route::middleware(['can:'. Permission::READ_USER->value])->get('/', [UserController::class, 'index'])->name('api.users.index');
    Route::middleware(['can:'. Permission::READ_USER->value])->get('/{id}', [UserController::class, 'show'])->name('api.users.show');
});

Route::prefix('institutes')->group(function () {
    Route::get('/', [InstituteController::class, 'index'])->name('api.institutes.index');
    Route::get('/{id}', [InstituteController::class, 'show'])->name('api.institutes.show');
});
