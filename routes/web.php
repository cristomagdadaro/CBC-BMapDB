<?php

use App\Http\Controllers\API\ApplicationController;
use App\Http\Controllers\API\BreederController;
use App\Http\Controllers\API\CommodityController;
use App\Http\Controllers\API\InstituteController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\TWGController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\SupportInfoController;
use App\Http\Middleware\AdminApprovedUser;
use App\Mail\UserInvitationEmail;
use App\Models\Breeder;
use App\Models\Commodity;
use App\Models\TWGExpert;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Auth\GoogleController;

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
    // Query to get breeders data joined with city location
    $data = Breeder::join('loc_cities', 'loc_cities.id', '=', 'breeders.geolocation')
        ->selectRaw('loc_cities.id, loc_cities.provDesc as label, COUNT(*) as total')
        ->groupBy('loc_cities.provDesc')
        ->groupBy('loc_cities.id')
        ->orderByDesc('total')
        ->get();

    $formattedData = $data->map(function ($item) {
        return [
            'id' => $item->id,
            'key' => Str::slug($item->label), //replace whitespace with dash (-)
            'province' => $item->label,
            'data' => $item->total,
        ];
    });

    return Inertia::render('Projects', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
        'breedersmap_overview' => $formattedData,
    ]);
});

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('auth.google.callback'); // This must be GET, not POST

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
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

Route::get('/accept-breeder-role/{user}', [InvitationController::class, 'acceptBreederRole'])
    ->name('accept.breeder.role')
    ->middleware('signed'); // Ensure the URL is signed

/* Public Api */
Route::prefix('/api/public')->group(function () {
    Route::get('/institutes', [InstituteController::class, 'index'])->name('api.institutes.index.public');
    Route::get('/applications', [ApplicationController::class, 'index'])->name('api.applications.index.public');
//    Route::get('/cities', [CityProvRegController::class, 'cityIndex'])->name('api.cities.index.public');
    Route::get('/roles', [RoleController::class, 'index'])->name('api.roles.index.public');
    Route::get('/commodities/summary', [CommodityController::class, 'summary'])->name('api.breedersmap.commodities.summary.public');
    Route::get('/breeders/summary', [BreederController::class, 'summary'])->name('api.breedersmap.breeders.summary.public');
});

Route::prefix('/support-info')->group(function () {
    Route::get('/what-is-pin', [SupportInfoController::class, 'whatIsPIN'])->name('support.what-is-pin');
    Route::get('/cbc-tour', [SupportInfoController::class, 'cbcTour'])->name('support.cbc-tour');
    Route::get('/terms-of-use', [SupportInfoController::class, 'termsOfUse'])->name('support.terms-of-use');
    //Route::get('/policy-notice', [SupportInfoController::class, 'policyNotice'])->name('support.policy-notice');
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

    Route::get('/breedersmap-db', function (Request $request) {
        return Inertia::render('Projects/BreedersMap/presentation/BreedersMapPublic', [
            'breadcrumbs' => [['label' => 'Home', 'to' => '/']],
            'params' => $request->all(),
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
                'view' => User::with(['accounts', 'roles', 'permissions'])->findOrFail($id),
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
                    'expert' => TWGExpert::find($id),
                    'breadcrumbs' => [['label' => 'Experts', 'to' => '/projects/twgdb/expert']],
                ]);
            })->name('twg.expert.view');
        });

        Route::middleware(['check.status.breedersmap'])->prefix('/breedersmap')->group(function () {
            Route::get('/{any?}', function () {
                return Inertia::render('Projects/BreedersMap/presentation/BreedersMapIndex');
            })->name('projects.breedersmap.index');

            Route::get('/breeder/{id}', function ($id) {
                $breeder = Breeder::find($id)->load(['affiliated', 'location','commodities']);

                return Inertia::render('Projects/BreedersMap/presentation/BreedersMapViewBreeder', [
                    'breeder' => $breeder,
                    'breadcrumbs' => [['label' => 'Breeders', 'to' => route('projects.breedersmap.index')]],
                ]);
            })->name('breedersmap.breeder.view');

            Route::get('/breeder/{id}/geomap', function ($id) {
                $breeder = Breeder::find($id)->load(['affiliated', 'location','commodities']);

                return Inertia::render('Projects/BreedersMap/presentation/BreedersMapViewBreeder', [
                    'breeder' => $breeder,
                    'breadcrumbs' => [['label' => 'Breeders', 'to' => route('projects.breedersmap.index')]],
                ]);
            })->name('breedersmap.breeder.geomap');

            Route::get('/commodity/{id}', function () {
                return Inertia::render('Projects/BreedersMap/presentation/BreedersMapViewCommodity', [
                    'commodity' => Commodity::find(request()->id)->load('location','breeder','characteristics','additionalinfo'),
                    'breadcrumbs' => [['label' => 'Commodities', 'to' => route('projects.breedersmap.index')]],
                ]);
            })->name('breedersmap.commodity.view');

            Route::get('/settings', function () {
                return Inertia::render('Projects/BreedersMap/presentation/components/misc/BmSettings');
            })->name('projects.breedersmap.settings');
        });
    });
});
