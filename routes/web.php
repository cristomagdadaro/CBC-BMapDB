<?php

use App\Http\Controllers\API\AccountForController;
use App\Http\Controllers\API\ApplicationController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\RolesController;
use App\Http\Controllers\DataTable\DataTableController;
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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});

Route::prefix('datatable')->group(function () {
    Route::get('index', [DataTableController::class, 'index'])->name('datatable.index');
});

Route::prefix('projects')->group(function () {
    Route::prefix('twg')->group(function () {
        Route::get('/', [TWGProjectsController::class, 'indexPage'])->name('twg.database');
        Route::get('create', [TWGProjectsController::class, 'create'])->name('twg.create');
        /* TWG Project Routes */
        Route::get('table-projects', [TWGProjectsController::class, 'tableProjects'])->name('twg.table.projects');
        Route::delete('destroy-project/{id}',  [TWGProjectsController::class, 'destroyProject'])->name('twg.destroy.project');
        Route::get('export', [TWGProjectsController::class, 'exportProject'])->name('twg.export.project');
        Route::get('edit/{id}', [TWGProjectsController::class, 'edit'])->name('twg.edit');
        Route::post('store', [TWGProjectsController::class, 'store'])->name('twg.project.store'); //optimized
        Route::get('edit/data/{id}', [TWGProjectsController::class, 'editData'])->name('twg.edit.project.data');
        Route::put('update/project/{id}', [TWGProjectsController::class, 'updateProject'])->name('twg.project.update');
        /* TWG Products Routes*/
        Route::get('table-products', [TWGProjectsController::class, 'tableProducts'])->name('twg.table.products');

        /* TWG User Management Routes*/
        Route::post('personal/update/{id}', [TWGProjectsController::class, 'updatePersonal'])->name('twgexpert.personal.update');
        Route::post('background/update/{id}', [TWGProjectsController::class, 'updateBackground'])->name('twgexpert.background.update');
        Route::post('account/update/{id}', [TWGProjectsController::class, 'updateAccount'])->name('twgexpert.account.update');
    });

    Route::get('breedersmap', function (){
        return Inertia::render('Projects/BreedersMap');
    })->name('breeders.map');

});

Route::middleware(['auth:sanctum'])->group(function (){
    Route::apiResource('/roles', RolesController::class);
    Route::apiResource('/permissions', PermissionController::class);
    Route::apiResource('/applications', ApplicationController::class);
    Route::apiResource('/account-for', AccountForController::class);
    Route::get('{id}/accounts', [AccountForController::class, 'index'])->name('account.for.accounts');
});

