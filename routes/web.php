<?php

use App\Enums\Permission;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\InstituteController;
use App\Http\Controllers\CityProvRegController;
use App\Http\Controllers\SupportInfoController;
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


Route::prefix('/support-info')->group(function () {
    Route::get('/about-us', [SupportInfoController::class, 'aboutUs'])->name('support.about-us');
    Route::get('/terms-of-use', [SupportInfoController::class, 'termsOfUse'])->name('support.terms-of-use');
    Route::get('/policy-notice', [SupportInfoController::class, 'policyNotice'])->name('support.policy-notice');
    Route::get('/privacy-policy', [SupportInfoController::class, 'privacyPolicy'])->name('support.privacy-policy');
    Route::get('/sitemap', [SupportInfoController::class, 'sitemap'])->name('support.sitemap');
    Route::get('/developers', [SupportInfoController::class, 'developers'])->name('support.developers');
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
                    $breeder = Breeder::find($id)->load(['affiliated', 'location', 'commodities']);
                //else
                    //$breeder = Breeder::where('user_id', Auth::id())->find($id)->load(['affiliated', 'location']);

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
