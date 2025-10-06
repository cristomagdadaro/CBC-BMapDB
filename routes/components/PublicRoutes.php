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
