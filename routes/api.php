<?php

use App\Http\Controllers\API\ApplicationController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\RolesController;
use App\Http\Controllers\API\TWGController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('/roles', RolesController::class);
Route::apiResource('/permissions', PermissionController::class);
Route::apiResource('/applications', ApplicationController::class);

Route::prefix('twg')->group(function (){
    //Routes for TWG Projects
    Route::prefix('projects')->group(function (){
        Route::get('/',[TWGController::class,'index'])->name('twg.projects.index');
        Route::get('/datatable',[TWGController::class,'dataTable'])->name('twg.projects.datatable');
    });
});





