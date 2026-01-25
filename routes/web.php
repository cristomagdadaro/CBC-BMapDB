<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\SupportInfoController;
use App\Http\Middleware\AdminApprovedUser;
use App\Mail\UserInvitationEmail;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Modules\PbMap\Models\Breeder;
use Modules\PbMap\Models\Commodity;
use Modules\TwgDb\Controllers\TWGController;
use Modules\TwgDb\Models\TWGExpert;

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
    // temporary: delete this after RCBS 2026
    Route::get('/forms/event/0504', function () {
        return redirect()->away('https://dacbc.philrice.gov.ph');
    });
    
    $data = Breeder::join('loc_cities', 'loc_cities.id', '=', 'breeders.geolocation')
        ->selectRaw('loc_cities.provDesc as label, COUNT(*) as total')
        ->groupBy('loc_cities.provDesc')
        ->orderByDesc('total')
        ->get();

    $formattedData = $data->map(function ($item) {
        return [
            'id' => Str::slug($item->label),
            'key' => Str::slug($item->label),
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
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/activity', [DashboardController::class, 'updateActivity'])->name('dashboard.activity');
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

Route::post('/accept-breeder-role/{user}/regenerate', [InvitationController::class, 'regenerateBreederInvite'])
    ->name('accept.breeder.role.regenerate')
    ->middleware('auth');

Route::prefix('/support-info')->group(function () {
    Route::get('/what-is-pin', [SupportInfoController::class, 'whatIsPIN'])->name('support.what-is-pin');
    Route::get('/terms-of-use', [SupportInfoController::class, 'termsOfUse'])->name('support.terms-of-use');
    //Route::get('/policy-notice', [SupportInfoController::class, 'policyNotice'])->name('support.policy-notice');
    Route::get('/privacy-policy', [SupportInfoController::class, 'privacyPolicy'])->name('support.privacy-policy');
    Route::get('/data-privacy', [SupportInfoController::class, 'dataPrivacy'])->name('support.data-privacy');
    Route::get('/sitemap', [SupportInfoController::class, 'sitemap'])->name('support.sitemap');
    Route::get('/contributors', [SupportInfoController::class, 'contributors'])->name('support.contributors');
});

Route::get('/sitemap.xml', [SupportInfoController::class, 'sitemapXml'])->name('sitemap.xml');


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
                    'commodity' => Commodity::findOrFail(request()->id)->load('location','breeder','characteristics','additionalinfo'),
                    'breadcrumbs' => [['label' => 'Commodities', 'to' => route('projects.breedersmap.index')]],
                ]);
            })->name('breedersmap.commodity.view');

            Route::get('/settings', function () {
                return Inertia::render('Projects/BreedersMap/presentation/components/misc/BmSettings');
            })->name('projects.breedersmap.settings');
        });
    });
});
