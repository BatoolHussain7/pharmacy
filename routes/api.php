<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
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

Route::prefix('admin')->group(function () {
    Route::post('register', [\App\Http\Controllers\Auth\AdminController::class, 'register']);
    Route::post('login', [\App\Http\Controllers\Auth\AdminController::class, 'login']);


    Route::middleware(['auth:admin-api'])->group(function () {
        Route::get('info', function () {
            return response()->json([
                'data' => \Illuminate\Support\Facades\Auth::user(),
                'message' => 'success'
            ]);
        });
        Route::post('AddProduct', [\App\Http\Controllers\Auth\AdminController::class, 'AddProduct']);
        //Route::get('logout', [\App\Http\Controllers\Auth\AdminController::class, 'logout']);
        Route::post('AddInfo/{product_id}', [\App\Http\Controllers\Auth\AdminController::class, 'AddInfo']);
        Route::get('totalAmount/{product_id}', [\App\Http\Controllers\Auth\AdminController::class, 'totalAmount']);
        Route::post('getPrice', [\App\Http\Controllers\Auth\AdminController::class, 'getPrice']);
        Route::post('createBill', [\App\Http\Controllers\Auth\AdminController::class, 'createBill']);
    });

});


Route::prefix('company')->group(function () {
    Route::post('register', [\App\Http\Controllers\Auth\CompanyController::class, 'register']);
    Route::post('login', [\App\Http\Controllers\Auth\CompanyController::class, 'login']);


    Route::middleware(['auth:company-api'])->group(function () {
        Route::get('info', function () {
            return response()->json([
                'data' => \Illuminate\Support\Facades\Auth::user(),
                'message' => 'success'
            ]);
        });

        Route::post('AddProduct', [\App\Http\Controllers\ProductController::class, 'AddProduct']);
    });
});
