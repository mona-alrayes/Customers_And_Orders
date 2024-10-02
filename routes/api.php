<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
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
Route::get('/customers/{id}/orders', [OrderController::class, 'customerOrders']);
Route::get('/customers/showDeleted', [CustomerController::class, 'showDeleted']);
Route::get('/customers/{id}/restoreDeleted', [CustomerController::class, 'restoreDeleted']);
Route::delete('/customers/{id}/forceDelete', [CustomerController::class, 'forceDeleted']);
Route::apiResource('/customers', CustomerController::class);
Route::get('/orders/showDeleted', [OrderController::class, 'showDeleted']);
Route::get('/orders/{id}/restoreDeleted', [OrderController::class, 'restoreDeleted']);
Route::delete('/orders/{id}/forceDelete', [OrderController::class, 'forceDeleted']);
Route::apiResource('/orders',OrderController::class);
