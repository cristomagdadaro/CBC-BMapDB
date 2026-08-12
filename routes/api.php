<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DashboardApiController;
use App\Http\Controllers\API\ActivityLogController;
use App\Http\Controllers\DataViewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|-------------------------------------------------------------------------
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

Route::prefix('/auth')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('register', 'register')->name('api.register');
        Route::post('login', 'login')->name('api.login');

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', 'logout');
            Route::get('user', 'user');
        });
    });
});

require 'components/PublicRoutes.php';

Route::middleware(['api','auth:sanctum','verified'])->group(function() {
    require base_path('modules/TwgDb/Routes/TWGDbRoutes.php');
    require base_path('modules/PbMap/Routes/BreedersMapRoutes.php');
    require 'components/SystemRoutes.php';

    // Dashboard API Routes
    Route::prefix('dashboard')->controller(DashboardApiController::class)->group(function () {
        Route::get('/system-stats', 'getSystemStats')->name('api.dashboard.system-stats');
        Route::get('/online-users', 'getOnlineUsers')->name('api.dashboard.online-users');
        Route::get('/recent-users', 'getRecentUsers')->name('api.dashboard.recent-users');
        Route::get('/user-role-distribution', 'getUserRoleDistribution')->name('api.dashboard.user-role-distribution');
        Route::get('/system-activities', 'getSystemActivities')->name('api.dashboard.system-activities');
        Route::post('/activity', 'updateActivity')->name('api.dashboard.activity');
    });

    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('api.activity-logs.index');

    Route::controller(DataViewController::class)->group(function () {
       Route::get('/data-view', 'index')->name('api.dataview.index');
       Route::get('/data-view/{table?}', 'show')->name('api.dataview.show');
       Route::post('/data-view/{table?}', 'store')->name('api.dataview.store');
       Route::put('/data-view/{table?}/{uuid?}', 'update')->name('api.dataview.update');
    });
});

