<?php

use Illuminate\Support\Facades\Route;
use Modules\PbMap\Controllers\BreederController;
use Modules\PbMap\Controllers\CommodityController;

/*Breeders' Map Related APIs*/
Route::middleware(['check.status.breedersmap', 'auth:sanctum'])->prefix('breeders')->group(function () {
    Route::get('/', [BreederController::class, 'index'])->name('api.breeders.index');
    Route::get('/selections', [BreederController::class, 'selection'])->name('api.breeders.selections');
    Route::get('/search', [BreederController::class, '_noPageSearch'])->name('api.breeders.noPageSearch');
    Route::get('/summary/{parent_id?}/', [BreederController::class, 'summary'])->name('api.breeders.summary');
    Route::get('/{id}', [BreederController::class, 'show'])->name('api.breeders.show');
    Route::post('/', [BreederController::class, 'store'])->name('api.breeders.store');
    Route::put('/{id}', [BreederController::class, 'update'])->name('api.breeders.update');
    Route::delete('/delete', [BreederController::class, 'multiDestroy'])->name('api.breeders.destroy.multi');
    Route::delete('/{id}', [BreederController::class, 'destroy'])->name('api.breeders.destroy');
});

Route::middleware(['check.status.breedersmap'])->prefix('commodities')->group(function () {
    Route::get('/', [CommodityController::class, 'index'])->name('api.commodities.index');
    Route::get('/summary', [CommodityController::class, 'summary'])->name('api.commodities.summary');
    Route::get('/{id}', [CommodityController::class, 'show'])->name('api.commodities.show');
    Route::post('/', [CommodityController::class, 'store'])->name('api.commodities.store');
    Route::put('/{id}', [CommodityController::class, 'update'])->name('api.commodities.update');
    Route::delete('/delete', [CommodityController::class, 'multiDestroy'])->name('api.commodities.destroy.multi');
    Route::delete('/{id}', [CommodityController::class, 'destroy'])->name('api.commodities.destroy');
});
