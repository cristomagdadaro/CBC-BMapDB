<?php

use App\Enums\Permission;
use App\Http\Controllers\API\TWGController;
use App\Http\Controllers\API\TWGExpertController;
use App\Http\Controllers\API\TWGProductController;
use App\Http\Controllers\API\TWGProjectController;
use App\Http\Controllers\API\TWGServiceController;
use Illuminate\Support\Facades\Route;

/*TWG Related APIs*/
Route::middleware(['check.status.twg'])->prefix('twg')->group(function () {
    Route::prefix('summary')->group(function () {
        Route::get('/', [TWGController::class, 'summary'])->name('api.twg.summary');
    });

    Route::prefix('experts')->group(function () {
        Route::middleware(['can:'. Permission::READ_TWG_EXPERT->value])->get('/', [TWGExpertController::class, 'index'])->name('api.twg.experts.index');
        Route::middleware(['can:'. Permission::READ_TWG_EXPERT->value])->get('/{id}', [TWGExpertController::class, 'show'])->name('api.twg.experts.show');
        Route::middleware(['can:'. Permission::CREATE_TWG_EXPERT->value])->post('/', [TWGExpertController::class, 'store'])->name('api.twg.experts.store');
        Route::middleware(['can:'. Permission::UPDATE_TWG_EXPERT->value])->put('/{id}', [TWGExpertController::class, 'update'])->name('api.twg.experts.update');
        Route::middleware(['can:'. Permission::DELETE_TWG_EXPERT->value])->delete('/delete', [TWGExpertController::class, 'multiDestroy'])->name('api.twg.experts.destroy.multi');
        Route::middleware(['can:'. Permission::DELETE_TWG_EXPERT->value])->delete('/{id}', [TWGExpertController::class, 'destroy'])->name('api.twg.experts.destroy');
    });

    Route::prefix('projects')->group(function () {
        Route::middleware(['can:'. Permission::READ_TWG_PROJECT->value])->get('/', [TWGProjectController::class, 'index'])->name('api.twg.projects.index');
        Route::middleware(['can:'. Permission::READ_TWG_PROJECT->value])->get('/{id}', [TWGProjectController::class, 'show'])->name('api.twg.projects.show');
        Route::middleware(['can:'. Permission::CREATE_TWG_PROJECT->value])->post('/', [TWGProjectController::class, 'store'])->name('api.twg.projects.store');
        Route::middleware(['can:'. Permission::UPDATE_TWG_PROJECT->value])->put('/{id}', [TWGProjectController::class, 'update'])->name('api.twg.projects.update');
        Route::middleware(['can:'. Permission::DELETE_TWG_PROJECT->value])->delete('/delete', [TWGProjectController::class, 'multiDestroy'])->name('api.twg.projects.destroy.multi');
        Route::middleware(['can:'. Permission::DELETE_TWG_PROJECT->value])->delete('/{id}', [TWGProjectController::class, 'destroy'])->name('api.twg.projects.destroy');
    });

    Route::prefix('products')->group(function () {
        Route::middleware(['can:'. Permission::READ_TWG_PRODUCT->value])->get('/', [TWGProductController::class, 'index'])->name('api.twg.products.index');
        Route::middleware(['can:'. Permission::READ_TWG_PRODUCT->value])->get('/{id}', [TWGProductController::class, 'show'])->name('api.twg.products.show');
        Route::middleware(['can:'. Permission::CREATE_TWG_PRODUCT->value])->post('/', [TWGProductController::class, 'store'])->name('api.twg.products.store');
        Route::middleware(['can:'. Permission::UPDATE_TWG_PRODUCT->value])->put('/{id}', [TWGProductController::class, 'update'])->name('api.twg.products.update');
        Route::middleware(['can:'. Permission::DELETE_TWG_PRODUCT->value])->delete('/delete', [TWGProductController::class, 'multiDestroy'])->name('api.twg.products.destroy.multi');
        Route::middleware(['can:'. Permission::DELETE_TWG_PRODUCT->value])->delete('/{id}', [TWGProductController::class, 'destroy'])->name('api.twg.products.destroy');
    });

    Route::prefix('services')->group(function () {
        Route::middleware(['can:'. Permission::READ_TWG_SERVICE->value])->get('/', [TWGServiceController::class, 'index'])->name('api.twg.services.index');
        Route::middleware(['can:'. Permission::READ_TWG_SERVICE->value])->get('/{id}', [TWGServiceController::class, 'show'])->name('api.twg.services.show');
        Route::middleware(['can:'. Permission::CREATE_TWG_SERVICE->value])->post('/', [TWGServiceController::class, 'store'])->name('api.twg.services.store');
        Route::middleware(['can:'. Permission::UPDATE_TWG_SERVICE->value])->put('/{id}', [TWGServiceController::class, 'update'])->name('api.twg.services.update');
        Route::middleware(['can:'. Permission::DELETE_TWG_SERVICE->value])->delete('/delete', [TWGServiceController::class, 'multiDestroy'])->name('api.twg.services.destroy.multi');
        Route::middleware(['can:'. Permission::DELETE_TWG_SERVICE->value])->delete('/{id}', [TWGServiceController::class, 'destroy'])->name('api.twg.services.destroy');
    });
});
