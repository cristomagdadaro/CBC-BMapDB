<?php

use App\Enums\Permission;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\InstituteController;
use App\Http\Middleware\AdminApprovedUser;
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

Route::prefix('email')->group(function () {
    Route::get('/invite/{name}/{email}', function($name, $email) {
        Mail::to($email)->send(new UserInvitationEmail($name));
        return "Invitation sent to {$name} ({$email}).";
    })->name('email.invite');

    Route::get('/verification', function() {
        return Inertia::render('Auth/Register');
    })->name('email.verify');
});

/* Public Api */
Route::prefix('/api/public')->group(function () {
    Route::get('/institutes', [InstituteController::class, 'index'])->name('api.institutes.index.public');
    Route::get('/applications', [ApplicationController::class, 'index'])->name('api.applications.index.public');
    Route::get('/roles', [RoleController::class, 'index'])->name('api.roles.index.public');
    Route::get('/commodities/summary', [CommodityController::class, 'summary'])->name('api.breedersmap.commodities.summary.public');
    Route::get('/breeders/summary', [BreederController::class, 'summary'])->name('api.breedersmap.breeders.summary.public');
});


Route::prefix('/projects')->group(function () {
    Route::get('/', function () {
        return Inertia::render('Projects');
    })->name('projects');

    Route::get('/twg-db', function (){
        return Inertia::render('Projects/TWG/presentation/TWGPublic', [
            'breadcrumbs' => [['label' => 'Home', 'to' => '/']],
        ]);
    })->name('projects.twgdb.public');

    Route::prefix('/summary')->group(function () {
        Route::get('/twg-db', [TWGController::class, 'index'])->name('api.twg.summary.public');
    });

    Route::get('/breedersmap-db/{any?}', function (){
        return Inertia::render('Projects/BreedersMap/presentation/BreedersMapPublic', [
            'commodities' => Commodity::all(),
            'breadcrumbs' => [['label' => 'Home', 'to' => '/']],
        ]);
    })->name('projects.breedersmap.public');

    Route::get('/search', [CommodityController::class, 'noPage'])->name('api.commodities.noPage.public');
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    AdminApprovedUser::class,
])->group(function () {

    Route::middleware('admin')->prefix('administrator')->group(function () {
        Route::get('/{any?}', function () {
            return Inertia::render('Admin/Administrator');
        })->name('administrator.index');

        Route::get('/users/{id}', function ($id) {
            return Inertia::render('Admin/components/NewUser/ViewUserAccount', [
                'view' => \App\Models\User::with(['accounts', 'roles', 'permissions'])->findOrFail($id),
                'breadcrumbs' => [['label' => 'Users', 'to' => '/administrator/users']],
            ]);
        })->name('administrator.user.view');
    });

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::prefix('/projects')->group(function () {
        Route::middleware(['check.status.twg'])->prefix('/twgdb')->group(function () {
            Route::get('/{any?}', function () {
                return Inertia::render('Projects/TWG/presentation/TWGIndex');
            })->name('projects.twg.index');

            Route::get('/expert/{id}', function ($id) {
                return Inertia::render('Projects/TWG/presentation/components/expert/ViewExpert', [
                    'expert' => \App\Models\TWGExpert::find($id),
                    'breadcrumbs' => [['label' => 'Experts', 'to' => '/projects/twgdb/expert']],
                ]);
            })->name('twg.expert.view');
        });

        Route::middleware(['check.status.breedersmap'])->prefix('/breedersmap')->group(function () {
            Route::get('/{any?}', function () {
                return Inertia::render('Projects/BreedersMap/presentation/BreedersMapIndex');
            })->name('projects.breedersmap.index');

            Route::get('/breeder/{id}', function ($id) {

                //if (Auth::user()->isAdmin())
                    $breeder = Breeder::find($id)->load('commodities');
                //else
                    //$breeder = Breeder::where('user_id', Auth::id())->find($id)->load('commodities');

                return Inertia::render('Projects/BreedersMap/presentation/BreedersMapViewBreeder', [
                    'breeder' => $breeder,
                    'breadcrumbs' => [['label' => 'Breeders', 'to' => route('projects.breedersmap.index')]],
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

Route::middleware(['auth:sanctum','verified'])->prefix('/api')->group(function() {
    /*Admin Related APIs*/
    Route::middleware('admin')->prefix('administrator')->group(function () {
        Route::middleware(['can:'. Permission::READ_USER->value])->get('/', [AdminController::class, 'index'])->name('api.administrator.index');
        Route::middleware(['can:'. Permission::READ_USER->value])->get('/{id}', [AdminController::class, 'show'])->name('api.administrator.show');
        Route::middleware(['can:'. Permission::CREATE_USER->value])->post('/', [AdminController::class, 'store'])->name('api.administrator.store');
        Route::middleware(['can:'. Permission::UPDATE_USER->value])->put('/{id}', [AdminController::class, 'update'])->name('api.administrator.update');
        Route::middleware(['can:'. Permission::DELETE_USER->value])->delete('/{id}', [AdminController::class, 'destroy'])->name('api.administrator.destroy');
        Route::middleware(['can:'. Permission::DELETE_USER->value])->delete('/delete', [AdminController::class, 'multiDestroy'])->name('api.administrator.destroy.multi');
    });

    /*TWG Related APIs*/
    Route::middleware(['check.status.twg'])->prefix('twg')->group(function () {
        Route::prefix('summary')->group(function () {
            Route::get('/', [TWGController::class, 'summary'])->name('api.twg.summary');
        });

       Route::prefix('experts')->group(function () {
           Route::middleware(['can:'. Permission::READ_TWG_EXPERT->value])->get('/', [TWGExpertController::class, 'index'])->name('api.twg.experts.index');
           Route::middleware(['can:'. Permission::READ_TWG_EXPERT->value])->get('/{id}', [TWGExpertController::class, 'show'])->name('api.twg.experts.show');
           Route::middleware(['can:'. Permission::CREATE_TWG_EXPERT->value])->post('/', [TWGExpertController::class, 'store'])->name('api.twg.experts.store');
           Route::middleware(['can:'. Permission::UPDATE_TWG_EXPERT->value])->put('/{id}', [TWGExpertController::class, 'update'])->name('api.twg.experts.update');
           Route::middleware(['can:'. Permission::DELETE_TWG_EXPERT->value])->delete('/delete', [TWGExpertController::class, 'multiDestroy'])->name('api.twg.experts.destroy.multi');
           Route::middleware(['can:'. Permission::DELETE_TWG_EXPERT->value])->delete('/{id}', [TWGExpertController::class, 'destroy'])->name('api.twg.experts.destroy');
       });

        Route::prefix('projects')->group(function () {
            Route::middleware(['can:'. Permission::READ_TWG_PROJECT->value])->get('/', [TWGProjectController::class, 'index'])->name('api.twg.projects.index');
            Route::middleware(['can:'. Permission::READ_TWG_PROJECT->value])->get('/{id}', [TWGProjectController::class, 'show'])->name('api.twg.projects.show');
            Route::middleware(['can:'. Permission::CREATE_TWG_PROJECT->value])->post('/', [TWGProjectController::class, 'store'])->name('api.twg.projects.store');
            Route::middleware(['can:'. Permission::UPDATE_TWG_PROJECT->value])->put('/{id}', [TWGProjectController::class, 'update'])->name('api.twg.projects.update');
            Route::middleware(['can:'. Permission::DELETE_TWG_PROJECT->value])->delete('/delete', [TWGProjectController::class, 'multiDestroy'])->name('api.twg.projects.destroy.multi');
            Route::middleware(['can:'. Permission::DELETE_TWG_PROJECT->value])->delete('/{id}', [TWGProjectController::class, 'destroy'])->name('api.twg.projects.destroy');
        });

        Route::prefix('products')->group(function () {
            Route::middleware(['can:'. Permission::READ_TWG_PRODUCT->value])->get('/', [TWGProductController::class, 'index'])->name('api.twg.products.index');
            Route::middleware(['can:'. Permission::READ_TWG_PRODUCT->value])->get('/{id}', [TWGProductController::class, 'show'])->name('api.twg.products.show');
            Route::middleware(['can:'. Permission::CREATE_TWG_PRODUCT->value])->post('/', [TWGProductController::class, 'store'])->name('api.twg.products.store');
            Route::middleware(['can:'. Permission::UPDATE_TWG_PRODUCT->value])->put('/{id}', [TWGProductController::class, 'update'])->name('api.twg.products.update');
            Route::middleware(['can:'. Permission::DELETE_TWG_PRODUCT->value])->delete('/delete', [TWGProductController::class, 'multiDestroy'])->name('api.twg.products.destroy.multi');
            Route::middleware(['can:'. Permission::DELETE_TWG_PRODUCT->value])->delete('/{id}', [TWGProductController::class, 'destroy'])->name('api.twg.products.destroy');
        });

        Route::prefix('services')->group(function () {
            Route::middleware(['can:'. Permission::READ_TWG_SERVICE->value])->get('/', [TWGServiceController::class, 'index'])->name('api.twg.services.index');
            Route::middleware(['can:'. Permission::READ_TWG_SERVICE->value])->get('/{id}', [TWGServiceController::class, 'show'])->name('api.twg.services.show');
            Route::middleware(['can:'. Permission::CREATE_TWG_SERVICE->value])->post('/', [TWGServiceController::class, 'store'])->name('api.twg.services.store');
            Route::middleware(['can:'. Permission::UPDATE_TWG_SERVICE->value])->put('/{id}', [TWGServiceController::class, 'update'])->name('api.twg.services.update');
            Route::middleware(['can:'. Permission::DELETE_TWG_SERVICE->value])->delete('/delete', [TWGServiceController::class, 'multiDestroy'])->name('api.twg.services.destroy.multi');
            Route::middleware(['can:'. Permission::DELETE_TWG_SERVICE->value])->delete('/{id}', [TWGServiceController::class, 'destroy'])->name('api.twg.services.destroy');
        });
    });

    /*Breeders' Map Related APIs*/
    Route::middleware(['check.status.breedersmap'])->prefix('breeders')->group(function () {
        Route::middleware('can:'. Permission::READ_BREEDER->value)->get('/', [BreederController::class, 'index'])->name('api.breeders.index');
        Route::middleware('can:'. Permission::READ_BREEDER->value)->get('/{id}', [BreederController::class, 'show'])->name('api.breeders.show');
        Route::middleware('can:'. Permission::READ_BREEDER->value)->get('/search/{id}', [BreederController::class, 'noPageSearch'])->name('api.breeders.noPageSearch');
        Route::middleware('can:'. Permission::CREATE_BREEDER->value)->post('/', [BreederController::class, 'store'])->name('api.breeders.store');
        Route::middleware('can:'. Permission::UPDATE_BREEDER->value)->put('/{id}', [BreederController::class, 'update'])->name('api.breeders.update');
        Route::middleware('can:'. Permission::DELETE_BREEDER->value)->delete('/delete', [BreederController::class, 'multiDestroy'])->name('api.breeders.destroy.multi');
        Route::middleware('can:'. Permission::DELETE_BREEDER->value)->delete('/{id}', [BreederController::class, 'destroy'])->name('api.breeders.destroy');
    });

    Route::middleware(['check.status.breedersmap'])->prefix('commodities')->group(function () {
       Route::middleware('can:'. Permission::READ_COMMODITY->value)->get('/{id?}', [CommodityController::class, 'index'])->name('api.commodities.index');
       Route::middleware('can:'. Permission::READ_COMMODITY->value)->get('/view-in-map', [CommodityController::class, 'noPage'])->name('api.commodities.noPage');
       Route::middleware('can:'. Permission::READ_COMMODITY->value)->get('/{id}', [CommodityController::class, 'show'])->name('api.commodities.show');
       Route::middleware('can:'. Permission::CREATE_COMMODITY->value)->post('/', [CommodityController::class, 'store'])->name('api.commodities.store');
       Route::middleware('can:'. Permission::UPDATE_COMMODITY->value)->put('/{id}', [CommodityController::class, 'update'])->name('api.commodities.update');
       Route::middleware('can:'. Permission::DELETE_COMMODITY->value)->delete('/delete', [CommodityController::class, 'multiDestroy'])->name('api.commodities.destroy.multi');
       Route::middleware('can:'. Permission::DELETE_COMMODITY->value)->delete('/{id}', [CommodityController::class, 'destroy'])->name('api.commodities.destroy');
    });

    Route::middleware(['check.status.breedersmap'])->prefix('geodata')->group(function () {
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
        Route::middleware(['can:'. Permission::READ_APP->value])->get('/', [ApplicationController::class, 'index'])->name('api.applications.index');
        Route::middleware(['can:'. Permission::READ_APP->value])->get('/{id}', [ApplicationController::class, 'show'])->name('api.applications.show');
        Route::middleware(['can:'. Permission::CREATE_APP->value])->post('/', [ApplicationController::class, 'store'])->name('api.applications.store');
        Route::middleware(['can:'. Permission::UPDATE_APP->value])->put('/{id}', [ApplicationController::class, 'update'])->name('api.applications.update');
        Route::middleware(['can:'. Permission::DELETE_APP->value])->delete('/{id}', [ApplicationController::class, 'destroy'])->name('api.applications.destroy');
    });

    Route::prefix('users')->group(function () {
        Route::middleware(['can:'. Permission::READ_USER->value])->get('/', [UserController::class, 'index'])->name('api.users.index');
        Route::middleware(['can:'. Permission::READ_USER->value])->get('/{id}', [UserController::class, 'show'])->name('api.users.show');
    });

    Route::prefix('institutes')->group(function () {
        Route::get('/', [InstituteController::class, 'index'])->name('api.institutes.index');
        Route::get('/{id}', [InstituteController::class, 'show'])->name('api.institutes.show');
    });

    Route::prefix('accounts')->group(function () {
        Route::middleware(['can:'. Permission::READ_APP_ACCOUNT->value])->get('/', [AccountController::class, 'index'])->name('api.accounts.index');
        Route::middleware(['can:'. Permission::READ_APP_ACCOUNT->value])->get('/{id}', [AccountController::class, 'show'])->name('api.accounts.show');
        Route::middleware(['can:'. Permission::CREATE_APP_ACCOUNT->value])->post('/', [AccountController::class, 'store'])->name('api.accounts.store');
        Route::middleware(['can:'. Permission::UPDATE_APP_ACCOUNT->value])->put('/{id}', [AccountController::class, 'update'])->name('api.accounts.update');
        Route::middleware(['can:'. Permission::DELETE_APP_ACCOUNT->value])->delete('/{id}', [AccountController::class, 'destroy'])->name('api.accounts.destroy');
    });
});
