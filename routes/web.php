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
use App\Http\Controllers\TWGProjectsController;
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

Route::middleware('public')->prefix('public')->group(function () {
    Route::get('/breedersmap', function (){
        return Inertia::render('Projects/BreedersMap/BreedersMap');
    })->name('projects.breedersmap.index');
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::prefix('/projects')->group(function () {
        Route::prefix('/twg')->group(function () {
            Route::get('/', [TWGProjectsController::class, 'index']);
            Route::get('/', [TWGProjectsController::class, 'indexPage'])->name('projects.twgdb.index');
            Route::get('/{id}', [TWGProjectsController::class, 'show']);
            Route::post('/', [TWGProjectsController::class, 'store']);
            Route::put('/{id}', [TWGProjectsController::class, 'update']);
            Route::delete('/{id}', [TWGProjectsController::class, 'destroy']);
        });

        Route::get('/breedersmap', function (){
            return Inertia::render('Projects/BreedersMap/BreedersMapIndex');
        })->name('projects.breedersmap.index');
    });
});

Route::middleware('auth:sanctum')->prefix('/api')->group(function() {

    Route::prefix('/breeders')->group(function () {
        Route::get('/', [BreederController::class, 'index'])->name('api.breeders.index');
        Route::get('/{id}', [BreederController::class, 'show'])->name('api.breeders.show');
        Route::post('/', [BreederController::class, 'store'])->name('api.breeders.store');
        Route::put('/{id}', [BreederController::class, 'update'])->name('api.breeders.update');
        Route::delete('/delete', [BreederController::class, 'multiDestroy'])->name('api.breeders.destroy.multi');
        Route::delete('/{id}', [BreederController::class, 'destroy'])->name('api.breeders.destroy');
    });

    Route::prefix('/roles')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('api.roles.index');
        Route::get('/{id}', [RoleController::class, 'show'])->name('api.roles.show');
        Route::post('/', [RoleController::class, 'store'])->name('api.roles.store');
        Route::put('/{id}', [RoleController::class, 'update'])->name('api.roles.update');
        Route::delete('/{id}', [RoleController::class, 'destroy'])->name('api.roles.destroy');
    });

    Route::prefix('/permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('api.permissions.index');
        Route::get('/{id}', [PermissionController::class, 'show'])->name('api.permissions.show');
        Route::post('/', [PermissionController::class, 'store'])->name('api.permissions.store');
        Route::put('/{id}', [PermissionController::class, 'update'])->name('api.permissions.update');
        Route::delete('/{id}', [PermissionController::class, 'destroy'])->name('api.permissions.destroy');
    });

    Route::prefix('/applications')->group(function () {
        Route::get('/', [ApplicationController::class, 'index'])->name('api.applications.index');
        Route::get('/{id}', [ApplicationController::class, 'show'])->name('api.applications.show');
        Route::post('/', [ApplicationController::class, 'store'])->name('api.applications.store');
        Route::put('/{id}', [ApplicationController::class, 'update'])->name('api.applications.update');
        Route::delete('/{id}', [ApplicationController::class, 'destroy'])->name('api.applications.destroy');
    });

    Route::prefix('/users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('api.users.index');
    });

    Route::prefix('/accounts')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('api.accounts.index');
        Route::get('/{id}', [AccountController::class, 'show'])->name('api.accounts.show');
        Route::post('/', [AccountController::class, 'store'])->name('api.accounts.store');
        Route::put('/{id}', [AccountController::class, 'update'])->name('api.accounts.update');
        Route::delete('/{id}', [AccountController::class, 'destroy'])->name('api.accounts.destroy');
    });
});
