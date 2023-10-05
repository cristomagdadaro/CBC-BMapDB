<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\TWGController;
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

Route::prefix('projects')->group(function () {
    Route::prefix('twg')->group(function () {
        Route::get('/', [TWGController::class, 'index'])->name('twg.database');
        Route::get('create', [TWGController::class, 'create'])->name('twg.create');

        Route::get('edit/{id}', [TWGController::class, 'edit'])->name('twg.edit');
        Route::get('edit/data/{id}', [TWGController::class, 'editdata'])->name('twg.edit.project.data');
        Route::put('update/project/{id}', [TWGController::class, 'updateProject'])->name('twg.project.update');

        Route::get('table-projects', [TWGController::class, 'tableprojects'])->name('twg.table.projects');
        Route::delete('destroy-project/{id}',  [TWGController::class, 'destroyProject'])->name('twg.destroy.project');
        Route::get('export', [TWGController::class, 'exportproject'])->name('twg.export.project');

        Route::post('personal/update/{id}', [TWGController::class, 'updatePersonal'])->name('twgexpert.personal.update');
        Route::post('background/update/{id}', [TWGController::class, 'updateBackground'])->name('twgexpert.background.update');
        Route::post('account/update/{id}', [TWGController::class, 'updateAccount'])->name('twgexpert.account.update');

        Route::post('store', [TWGController::class, 'twgprojectstore'])->name('twg.project.store');
    });

    Route::get('/breedersmap', function (){
        return Inertia::render('Projects/BreedersMap');
    })->name('breedersmap');
});

