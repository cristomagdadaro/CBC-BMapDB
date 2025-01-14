<?php

use App\Enums\Permission;
use App\Http\Controllers\API\BreederController;
use App\Http\Controllers\API\CommodityController;
use Illuminate\Support\Facades\Route;

/*Breeders' Map Related APIs*/
Route::middleware(['check.status.breedersmap'])->prefix('breeders')->group(function () {
    Route::middleware('can:'. Permission::READ_BREEDER->value)->get('/', [BreederController::class, 'index'])->name('api.breeders.index');
    Route::middleware('can:'. Permission::READ_BREEDER->value)->get('/search/{id}', [BreederController::class, 'noPageSearch'])->name('api.breeders.noPageSearch');
    Route::middleware('can:'. Permission::READ_BREEDER->value)->get('/summary/{parent_id?}/', [BreederController::class, 'summary'])->name('api.breeders.summary');
    Route::middleware('can:'. Permission::READ_BREEDER->value)->get('/{id}', [BreederController::class, 'show'])->name('api.breeders.show');
    Route::middleware('can:'. Permission::CREATE_BREEDER->value)->post('/', [BreederController::class, 'store'])->name('api.breeders.store');
    Route::middleware('can:'. Permission::UPDATE_BREEDER->value)->put('/{id}', [BreederController::class, 'update'])->name('api.breeders.update');
    Route::middleware('can:'. Permission::DELETE_BREEDER->value)->delete('/delete', [BreederController::class, 'multiDestroy'])->name('api.breeders.destroy.multi');
    Route::middleware('can:'. Permission::DELETE_BREEDER->value)->delete('/{id}', [BreederController::class, 'destroy'])->name('api.breeders.destroy');
});

Route::middleware(['check.status.breedersmap'])->prefix('commodities')->group(function () {
    Route::middleware('can:'. Permission::READ_COMMODITY->value)->get('/', [CommodityController::class, 'index'])->name('api.commodities.index');
    Route::middleware('can:'. Permission::READ_COMMODITY->value)->get('/summary', [CommodityController::class, 'summary'])->name('api.commodities.summary');
    Route::middleware('can:'. Permission::READ_COMMODITY->value)->get('/{id}', [CommodityController::class, 'show'])->name('api.commodities.show');
    Route::middleware('can:'. Permission::CREATE_COMMODITY->value)->post('/', [CommodityController::class, 'store'])->name('api.commodities.store');
    Route::middleware('can:'. Permission::UPDATE_COMMODITY->value)->put('/{id}', [CommodityController::class, 'update'])->name('api.commodities.update');
    Route::middleware('can:'. Permission::DELETE_COMMODITY->value)->delete('/delete', [CommodityController::class, 'multiDestroy'])->name('api.commodities.destroy.multi');
    Route::middleware('can:'. Permission::DELETE_COMMODITY->value)->delete('/{id}', [CommodityController::class, 'destroy'])->name('api.commodities.destroy');
});
