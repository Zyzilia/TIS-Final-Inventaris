<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\ProxyController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::get('profile', [AuthController::class, 'profile']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

// Group Route yang Wajib Login
Route::middleware('auth:api')->group(function () {
    // Hak Akses: HANYA ADMIN (Create, Update, Delete)
    Route::middleware('role:admin')->group(function () {
        Route::post('items', [ItemController::class, 'store']);
        Route::put('items/{id}', [ItemController::class, 'update']);
        Route::delete('items/{id}', [ItemController::class, 'destroy']);
    });

    // Hak Akses: ADMIN & STAFF GUDANG (Read Only)
    Route::middleware('role:admin,staff')->group(function () {
        Route::get('items', [ItemController::class, 'index']);
        Route::get('items/{id}', [ItemController::class, 'show']);
        Route::get('activities', [ActivityController::class, 'index']);
        Route::get('transactions', [TransactionController::class, 'index']);
        Route::post('transactions', [TransactionController::class, 'store']);
        Route::put('transactions/{id}', [TransactionController::class, 'update']);

        Route::prefix('proxy')->group(function () {
            Route::get('provinces', [ProxyController::class, 'getProvinces']);
            Route::get('cities', [ProxyController::class, 'getCities']);
            Route::post('shipping-cost', [ProxyController::class, 'checkCost']);
            Route::get('currency-rates', [ProxyController::class, 'getCurrencyRates']);
        });
    });
});
