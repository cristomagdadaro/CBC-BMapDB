<?php

use App\Http\Controllers\API\ApplicationController;
use App\Http\Controllers\API\InstituteController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\CityProvRegController;
use Illuminate\Support\Facades\Route;
use Modules\PbMap\Controllers\BreederController;
use Modules\PbMap\Controllers\CommodityController;


/* Public Api */
Route::get('/institutes', [InstituteController::class, 'index'])->name('api.institutes.index.public');
Route::get('/applications', [ApplicationController::class, 'index'])->name('api.applications.index.public');
Route::get('/cities', [CityProvRegController::class, 'cityIndex'])->name('api.cities.index.public');
Route::get('/roles', [RoleController::class, 'index'])->name('api.roles.index.public');
Route::get('/commodities/summary', [CommodityController::class, 'summary'])->name('api.breedersmap.commodities.summary.public');
Route::get('/commodities/priority', [CommodityController::class, 'priorityCommodities'])->name('api.breedersmap.commodities.priority.public');
Route::get('/breeders/summary', [BreederController::class, 'summary'])->name('api.breedersmap.breeders.summary.public');
