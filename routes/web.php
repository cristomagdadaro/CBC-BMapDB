<?php

use App\Http\Controllers\API\AccountController;
use App\Http\Controllers\API\ApplicationController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BreederController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('HomePage', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::middleware('auth:sanctum')->prefix('/api')->group(function() {

    Route::prefix('/breeders')->group(function () {
        Route::get('/', [BreederController::class, 'index']);
        Route::get('/{id}', [BreederController::class, 'show']);
        Route::post('/', [BreederController::class, 'store']);
        Route::put('/{id}', [BreederController::class, 'update']);
        Route::delete('/{id}', [BreederController::class, 'destroy']);
    });

    Route::prefix('/roles')->group(function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::get('/{id}', [RoleController::class, 'show']);
        Route::post('/', [RoleController::class, 'store']);
        Route::put('/{id}', [RoleController::class, 'update']);
        Route::delete('/{id}', [RoleController::class, 'destroy']);
    });

    Route::prefix('/permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index']);
        Route::get('/{id}', [PermissionController::class, 'show']);
        Route::post('/', [PermissionController::class, 'store']);
        Route::put('/{id}', [PermissionController::class, 'update']);
        Route::delete('/{id}', [PermissionController::class, 'destroy']);
    });

    Route::prefix('/applications')->group(function () {
        Route::get('/', [ApplicationController::class, 'index']);
        Route::get('/{id}', [ApplicationController::class, 'show']);
        Route::post('/', [ApplicationController::class, 'store']);
        Route::put('/{id}', [ApplicationController::class, 'update']);
        Route::delete('/{id}', [ApplicationController::class, 'destroy']);
    });

    Route::prefix('/users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
    });

    Route::prefix('/accounts')->group(function () {
        Route::get('/', [AccountController::class, 'index']);
        Route::get('/{id}', [AccountController::class, 'show']);
        Route::post('/', [AccountController::class, 'store']);
        Route::put('/{id}', [AccountController::class, 'update']);
        Route::delete('/{id}', [AccountController::class, 'destroy']);
    });
});
