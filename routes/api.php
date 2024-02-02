<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\Inventory\ItemController;
use App\Http\Controllers\API\Inventory\SupplierController;
use App\Http\Controllers\API\Inventory\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
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
        Route::post('register', 'register')->name('register');
        Route::post('login', 'login')->name('login');

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout', 'logout');
            Route::get('user', 'user');
        });
    });
});

Route::prefix('/inventory')->group(function () {
    Route::controller(TransactionController::class)->group(function () {
        Route::get('transactions', 'index')->name('inventory.transactions.index');
        Route::post('transactions', 'store')->name('inventory.transactions.store');
        Route::get('transactions/{id}', 'show')->name('inventory.transactions.show');
        Route::put('transactions/{id}', 'update')->name('inventory.transactions.update');
        Route::delete('transactions/{id}', 'destroy')->name('inventory.transactions.destroy');
    });

    Route::controller(ItemController::class)->group(function () {
        Route::get('items', 'index')->name('inventory.items.index');
        Route::post('items', 'store')->name('inventory.items.store');
        Route::get('items/{id}', 'show')->name('inventory.items.show');
        Route::put('items/{id}', 'update')->name('inventory.items.update');
        Route::delete('items/{id}', 'destroy')->name('inventory.items.destroy');
    });

    Route::controller(SupplierController::class)->group(function () {
        Route::get('suppliers', 'index')->name('inventory.suppliers.index');
        Route::post('suppliers', 'store')->name('inventory.suppliers.store');
        Route::get('suppliers/{id}', 'show')->name('inventory.suppliers.show');
        Route::put('suppliers/{id}', 'update')->name('inventory.suppliers.update');
        Route::delete('suppliers/{id}', 'destroy')->name('inventory.suppliers.destroy');
    });
});
