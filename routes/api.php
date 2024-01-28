<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Admin\TransactionController;
use App\Http\Controllers\Api\Admin\PaymentsController;

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

// Login route for API
Route::post('/login', [LoginController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/transactions', [TransactionController::class, 'index']);
    Route::middleware(['adminrole'])->group(function () {

        Route::post('/transactions/create', [TransactionController::class, 'store']);
        Route::post('/payments/create', [PaymentsController::class, 'store']);
        Route::post('/transactions/financial-report', [TransactionController::class, 'financialReports']);
    });


    Route::post('/logout', [LoginController::class, 'logout']);
});
