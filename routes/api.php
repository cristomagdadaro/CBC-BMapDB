<?php

use App\Http\Controllers\API\AuthController;
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

require_once 'components/PublicRoutes.php';

Route::middleware(['api','auth:sanctum','verified'])->group(function() {
    require_once 'components/TWGDbRoutes.php';
    require_once 'components/BreedersMapRoutes.php';
    require_once 'components/SystemRoutes.php';
});
