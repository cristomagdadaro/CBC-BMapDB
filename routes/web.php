<?php

use App\Http\Controllers\API\AdminController;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\API\AccountController;
use App\Http\Controllers\API\ApplicationController;
use App\Http\Controllers\API\BreederController;
use App\Http\Controllers\API\CommodityController;
use App\Http\Controllers\API\GeodataController;
use App\Http\Controllers\API\PermissionController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\TWGController;
use App\Http\Controllers\API\TWGExpertController;
use App\Http\Controllers\API\TWGProductController;
use App\Http\Controllers\API\TWGProjectController;
use App\Http\Controllers\API\TWGServiceController;
use App\Http\Controllers\UserController;
use App\Mail\UserInvitationEmail;
use App\Models\Breeder;
use App\Models\Commodity;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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
    return Inertia::render('Projects', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/administrator', function () {
        return Inertia::render('Admin/Administrator');
    })->name('administrator.index');
});

Route::prefix('email')->group(function () {
    Route::get('/invite/{name}/{email}', function($name, $email) {
        Mail::to($email)->send(new UserInvitationEmail($name));
        return "Invitation sent to {$name} ({$email}).";
    })->name('email.invite');

    Route::get('/verification', function() {
        return Inertia::render('Auth/Register');
    })->name('email.verify');
});

Route::prefix('/projects')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Projects');
    })->name('projects');

    Route::get('/twg-db', function (){
        return Inertia::render('Projects/TWG/presentation/TWGPublic');
    })->name('projects.twgdb.public');

    Route::prefix('/summary')->group(function () {
        Route::get('/twg-db', [TWGController::class, 'index'])->name('api.twg.summary.public');
    });

    Route::get('/breedersmap-db', function (){
        return Inertia::render('Projects/BreedersMap/presentation/BreedersMapPublic', [
            'commodities' => Commodity::all(),
        ]);
    })->name('projects.breedersmap.public');

    Route::get('/search', [CommodityController::class, 'noPage'])->name('api.commodities.noPage.public');
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
        Route::get('/twg', function () {
            return Inertia::render('Projects/TWG/presentation/TWGIndex');
        })->name('projects.twg.index');

        Route::prefix('/breedersmap')->group(function () {
            Route::get('/', function () {
                return Inertia::render('Projects/BreedersMap/presentation/BreedersMapIndex');
            })->name('projects.breedersmap.index');

            Route::get('/breeder/{id}', function ($id) {
                $breeder = Breeder::where('id', $id)->where('user_id', Auth::id())->with('commodities')->firstOrFail();

                return Inertia::render('Projects/BreedersMap/presentation/BreedersMapViewBreeder', [
                    'breeder' => $breeder
                ]);
            })->name('breedersmap.breeder.view');

            Route::get('/commodity/{id}', function () {
                return Inertia::render('Projects/BreedersMap/presentation/BreedersMapViewCommodity', [
                    'commodity' => Commodity::find(request()->id)->load('breeder')
                ]);
            })->name('breedersmap.commodity.view');
        });
    });
});

Route::middleware('auth:sanctum')->prefix('/api')->group(function() {
    /*Admin Related APIs*/
    Route::middleware(['verified'])->prefix('administrator')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('api.administrator.index');
        Route::get('/{id}', [AdminController::class, 'show'])->name('api.administrator.show');
        Route::post('/', [AdminController::class, 'store'])->name('api.administrator.store');
        Route::put('/{id}', [AdminController::class, 'update'])->name('api.administrator.update');
        Route::delete('/{id}', [AdminController::class, 'destroy'])->name('api.administrator.destroy');
        Route::delete('/delete', [AdminController::class, 'multiDestroy'])->name('api.administrator.destroy.multi');
    });

    /*TWG Related APIs*/
    Route::prefix('twg')->group(function () {
        Route::prefix('summary')->group(function () {
            Route::get('/', [TWGController::class, 'summary'])->name('api.twg.summary');
        });

       Route::prefix('experts')->group(function () {
           Route::get('/', [TWGExpertController::class, 'index'])->name('api.twg.experts.index');
           Route::get('/{id}', [TWGExpertController::class, 'show'])->name('api.twg.experts.show');
           Route::post('/', [TWGExpertController::class, 'store'])->name('api.twg.experts.store');
           Route::put('/{id}', [TWGExpertController::class, 'update'])->name('api.twg.experts.update');
           Route::delete('/delete', [TWGExpertController::class, 'multiDestroy'])->name('api.twg.experts.destroy.multi');
           Route::delete('/{id}', [TWGExpertController::class, 'destroy'])->name('api.twg.experts.destroy');
       });

        Route::prefix('projects')->group(function () {
            Route::get('/', [TWGProjectController::class, 'index'])->name('api.twg.projects.index');
            Route::get('/{id}', [TWGProjectController::class, 'show'])->name('api.twg.projects.show');
            Route::post('/', [TWGProjectController::class, 'store'])->name('api.twg.projects.store');
            Route::put('/{id}', [TWGProjectController::class, 'update'])->name('api.twg.projects.update');
            Route::delete('/delete', [TWGProjectController::class, 'multiDestroy'])->name('api.twg.projects.destroy.multi');
            Route::delete('/{id}', [TWGProjectController::class, 'destroy'])->name('api.twg.projects.destroy');
        });

        Route::prefix('products')->group(function () {
            Route::get('/', [TWGProductController::class, 'index'])->name('api.twg.products.index');
            Route::get('/{id}', [TWGProductController::class, 'show'])->name('api.twg.products.show');
            Route::post('/', [TWGProductController::class, 'store'])->name('api.twg.products.store');
            Route::put('/{id}', [TWGProductController::class, 'update'])->name('api.twg.products.update');
            Route::delete('/delete', [TWGProductController::class, 'multiDestroy'])->name('api.twg.products.destroy.multi');
            Route::delete('/{id}', [TWGProductController::class, 'destroy'])->name('api.twg.products.destroy');
        });

        Route::prefix('services')->group(function () {
            Route::get('/', [TWGServiceController::class, 'index'])->name('api.twg.services.index');
            Route::get('/{id}', [TWGServiceController::class, 'show'])->name('api.twg.services.show');
            Route::post('/', [TWGServiceController::class, 'store'])->name('api.twg.services.store');
            Route::put('/{id}', [TWGServiceController::class, 'update'])->name('api.twg.services.update');
            Route::delete('/delete', [TWGServiceController::class, 'multiDestroy'])->name('api.twg.services.destroy.multi');
            Route::delete('/{id}', [TWGServiceController::class, 'destroy'])->name('api.twg.services.destroy');
        });
    });

    /*Breeders Map Related APIs*/
    Route::prefix('breeders')->group(function () {
        Route::middleware('can:read-breeder')->get('/', [BreederController::class, 'index'])->name('api.breeders.index');
        Route::middleware('can:read-breeder')->get('/{id}', [BreederController::class, 'show'])->name('api.breeders.show');
        Route::middleware('can:read-breeder')->get('/search/{id}', [BreederController::class, 'noPageSearch'])->name('api.breeders.noPageSearch');
        Route::middleware('can:create-breeder')->post('/', [BreederController::class, 'store'])->name('api.breeders.store');
        Route::middleware('can:update-breeder')->put('/{id}', [BreederController::class, 'update'])->name('api.breeders.update');
        Route::middleware('can:delete-breeder')->delete('/delete', [BreederController::class, 'multiDestroy'])->name('api.breeders.destroy.multi');
        Route::middleware('can:delete-breeder')->delete('/{id}', [BreederController::class, 'destroy'])->name('api.breeders.destroy');
    });

    Route::prefix('commodities')->group(function () {
       Route::middleware('can:read-breeder')->get('/', [CommodityController::class, 'index'])->name('api.commodities.index');
       Route::middleware('can:read-breeder')->get('/search', [CommodityController::class, 'noPage'])->name('api.commodities.noPage');
       Route::middleware('can:read-breeder')->get('/{id}', [CommodityController::class, 'show'])->name('api.commodities.show');
       Route::middleware('can:create-breeder')->post('/', [CommodityController::class, 'store'])->name('api.commodities.store');
       Route::middleware('can:update-breeder')->put('/{id}', [CommodityController::class, 'update'])->name('api.commodities.update');
       Route::middleware('can:delete-breeder')->delete('/delete', [CommodityController::class, 'multiDestroy'])->name('api.commodities.destroy.multi');
       Route::middleware('can:delete-breeder')->delete('/{id}', [CommodityController::class, 'destroy'])->name('api.commodities.destroy');
    });

    Route::prefix('geodata')->group(function () {
        Route::get('/', [GeodataController::Class, 'index'])->name('api.breeders.geodata.index');
    });

    /*System Related APIs*/

    Route::prefix('roles')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('api.roles.index');
        Route::get('/{id}', [RoleController::class, 'show'])->name('api.roles.show');
        Route::post('/', [RoleController::class, 'store'])->name('api.roles.store');
        Route::put('/{id}', [RoleController::class, 'update'])->name('api.roles.update');
        Route::delete('/{id}', [RoleController::class, 'destroy'])->name('api.roles.destroy');
    });

    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('api.permissions.index');
        Route::get('/{id}', [PermissionController::class, 'show'])->name('api.permissions.show');
        Route::post('/', [PermissionController::class, 'store'])->name('api.permissions.store');
        Route::put('/{id}', [PermissionController::class, 'update'])->name('api.permissions.update');
        Route::delete('/{id}', [PermissionController::class, 'destroy'])->name('api.permissions.destroy');
    });

    Route::prefix('applications')->group(function () {
        Route::get('/', [ApplicationController::class, 'index'])->name('api.applications.index');
        Route::get('/{id}', [ApplicationController::class, 'show'])->name('api.applications.show');
        Route::post('/', [ApplicationController::class, 'store'])->name('api.applications.store');
        Route::put('/{id}', [ApplicationController::class, 'update'])->name('api.applications.update');
        Route::delete('/{id}', [ApplicationController::class, 'destroy'])->name('api.applications.destroy');
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('api.users.index');
    });

    Route::prefix('accounts')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('api.accounts.index');
        Route::get('/{id}', [AccountController::class, 'show'])->name('api.accounts.show');
        Route::post('/', [AccountController::class, 'store'])->name('api.accounts.store');
        Route::put('/{id}', [AccountController::class, 'update'])->name('api.accounts.update');
        Route::delete('/{id}', [AccountController::class, 'destroy'])->name('api.accounts.destroy');
    });
});
