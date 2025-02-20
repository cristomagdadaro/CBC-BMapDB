<?php

use Illuminate\Support\Facades\Route;
use Modules\TwgDb\Controllers\TWGController;
use Modules\TwgDb\Controllers\TWGExpertController;
use Modules\TwgDb\Controllers\TWGProductController;
use Modules\TwgDb\Controllers\TWGProjectController;
use Modules\TwgDb\Controllers\TWGServiceController;

/*TWG Related APIs*/
Route::middleware(['check.status.twg'])->prefix('twg')->group(function () {
    Route::prefix('summary')->group(function () {
        Route::get('/', [TWGController::class, 'summary'])->name('api.twg.summary');
    });

    Route::prefix('experts')->group(function () {
        Route::get('/', [TWGExpertController::class, 'index'])->name('api.twg.experts.index');
        Route::get('/{id}', [TWGExpertController::class, 'show'])->name('api.twg.experts.show');
        Route::post('/', [TWGExpertController::class, 'store'])->name('api.twg.experts.store');
        Route::put('/{id}', [TWGExpertController::class, 'update'])->name('api.twg.experts.update');
        Route::delete('/delete', [TWGExpertController::class, 'multiDestroy'])->name('api.twg.experts.destroy.multi');
        Route::delete('/{id}', [TWGExpertController::class, 'destroy'])->name('api.twg.experts.destroy');
    });

    Route::prefix('projects')->group(function () {
        Route::get('/', [TWGProjectController::class, 'index'])->name('api.twg.projects.index');
        Route::get('/{id}', [TWGProjectController::class, 'show'])->name('api.twg.projects.show');
        Route::post('/', [TWGProjectController::class, 'store'])->name('api.twg.projects.store');
        Route::put('/{id}', [TWGProjectController::class, 'update'])->name('api.twg.projects.update');
        Route::delete('/delete', [TWGProjectController::class, 'multiDestroy'])->name('api.twg.projects.destroy.multi');
        Route::delete('/{id}', [TWGProjectController::class, 'destroy'])->name('api.twg.projects.destroy');
    });

    Route::prefix('products')->group(function () {
        Route::get('/', [TWGProductController::class, 'index'])->name('api.twg.products.index');
        Route::get('/{id}', [TWGProductController::class, 'show'])->name('api.twg.products.show');
        Route::post('/', [TWGProductController::class, 'store'])->name('api.twg.products.store');
        Route::put('/{id}', [TWGProductController::class, 'update'])->name('api.twg.products.update');
        Route::delete('/delete', [TWGProductController::class, 'multiDestroy'])->name('api.twg.products.destroy.multi');
        Route::delete('/{id}', [TWGProductController::class, 'destroy'])->name('api.twg.products.destroy');
    });

    Route::prefix('services')->group(function () {
        Route::get('/', [TWGServiceController::class, 'index'])->name('api.twg.services.index');
        Route::get('/{id}', [TWGServiceController::class, 'show'])->name('api.twg.services.show');
        Route::post('/', [TWGServiceController::class, 'store'])->name('api.twg.services.store');
        Route::put('/{id}', [TWGServiceController::class, 'update'])->name('api.twg.services.update');
        Route::delete('/delete', [TWGServiceController::class, 'multiDestroy'])->name('api.twg.services.destroy.multi');
        Route::delete('/{id}', [TWGServiceController::class, 'destroy'])->name('api.twg.services.destroy');
    });
});
