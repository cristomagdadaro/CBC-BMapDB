<?php

use App\Http\Controllers\API\ApplicationController;
use App\Http\Controllers\API\InstituteController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\CityProvRegController;
use Illuminate\Support\Facades\Route;
use Modules\PbMap\Controllers\BreederController;
use Modules\PbMap\Controllers\CommodityController;


/* Public Api - Selection Options */
Route::get('/institutes/options', [InstituteController::class, 'options'])->name('api.institutes.options.public');
Route::get('/applications/options', [ApplicationController::class, 'options'])->name('api.applications.options.public');
Route::get('/cities', [CityProvRegController::class, 'cityOptions'])->name('api.cities.options.public');
Route::get('/roles/options', [RoleController::class, 'options'])->name('api.roles.options.public');

/* Public Api - Summary Data */
Route::get('/commodities/summary', [CommodityController::class, 'summary'])->name('api.breedersmap.commodities.summary.public');
Route::get('/commodities/priority', [CommodityController::class, 'priorityCommodities'])->name('api.breedersmap.commodities.priority.public');
Route::get('/breeders/summary', [BreederController::class, 'summary'])->name('api.breedersmap.breeders.summary.public');

// New Map Data API Routes - Cleaner and more maintainable
// All are publicly accessible routes, when specific data are accessed such as full breeder info or commodity details,
// proper authorization checks should be implemented within the controller methods.
Route::prefix('map-data')->group(function () {
    Route::get('/', [App\Http\Controllers\API\MapDataController::class, 'getMapData'])->name('api.map-data');
    Route::get('/filter-options', [App\Http\Controllers\API\MapDataController::class, 'getFilterOptions'])->name('api.map-data.filter.options');
    Route::get('/summary', [App\Http\Controllers\API\MapDataController::class, 'getSummary'])->name('api.map-data.summary');
    Route::get('/geographic-distribution', [App\Http\Controllers\API\MapDataController::class, 'getGeographicDistribution']);
    Route::get('/orbit-items', [App\Http\Controllers\API\MapDataController::class, 'getOrbitItems'])->name('api.map-data.orbit-items');
});
