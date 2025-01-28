<?php
namespace App\Modules\Administrator\Routes;

use App\Modules\Administrator\Controllers\AdminController;
use App\Modules\Administrator\Controllers\AccountController;
use App\Enums\Permission;
use App\Modules\Administrator\Controllers\ApplicationController;
use Illuminate\Support\Facades\Route;

Route::middleware('admin')->prefix('administrator')->group(function () {
    Route::middleware(['can:'. Permission::READ_USER->value])->get('/', [AdminController::class, 'index'])->name('api.administrator.index');
    Route::middleware(['can:'. Permission::READ_USER->value])->get('/{id}', [AdminController::class, 'show'])->name('api.administrator.show');
    Route::middleware(['can:'. Permission::CREATE_USER->value])->post('/', [AdminController::class, 'store'])->name('api.administrator.store');
    Route::middleware(['can:'. Permission::UPDATE_USER->value])->put('/{id}', [AdminController::class, 'update'])->name('api.administrator.update');
    Route::middleware(['can:'. Permission::DELETE_USER->value])->delete('/delete', [AdminController:: class, 'multiDestroy'])->name('api.administrator.destroy.multi');
    Route::middleware(['can:'. Permission::DELETE_USER->value])->delete('/{id}', [AdminController::class, 'destroy'])->name('api.administrator.destroy');

});

Route::prefix('applications')->group(function () {
    Route::middleware(['can:'. Permission::READ_APP->value])->get('/', [ApplicationController::class, 'index'])->name('api.applications.index');
    Route::middleware(['can:'. Permission::READ_APP->value])->get('/{id}', [ApplicationController::class, 'show'])->name('api.applications.show');
    Route::middleware(['can:'. Permission::CREATE_APP->value])->post('/', [ApplicationController::class, 'store'])->name('api.applications.store');
    Route::middleware(['can:'. Permission::UPDATE_APP->value])->put('/{id}', [ApplicationController::class, 'update'])->name('api.applications.update');
    Route::middleware(['can:'. Permission::DELETE_APP->value])->delete('/{id}', [ApplicationController::class, 'destroy'])->name('api.applications.destroy');
});

Route::prefix('accounts')->group(function () {
    Route::middleware(['can:'. Permission::READ_APP_ACCOUNT->value])->get('/', [AccountController::class, 'index'])->name('api.accounts.index');
    Route::middleware(['can:'. Permission::READ_APP_ACCOUNT->value])->get('/{id}', [AccountController::class, 'show'])->name('api.accounts.show');
    Route::middleware(['can:'. Permission::CREATE_APP_ACCOUNT->value])->post('/', [AccountController::class, 'store'])->name('api.accounts.store');
    Route::middleware(['can:'. Permission::UPDATE_APP_ACCOUNT->value])->put('/{id}', [AccountController::class, 'update'])->name('api.accounts.update');
    Route::middleware(['can:'. Permission::DELETE_APP_ACCOUNT->value])->delete('/{id}', [AccountController::class, 'destroy'])->name('api.accounts.destroy');
});
