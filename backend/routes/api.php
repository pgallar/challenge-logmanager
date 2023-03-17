<?php

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

Route::post('order-notifications', [\App\Http\Controllers\OrderNotificationController::class, 'handle']);
Route::get('accounts', [\App\Http\Controllers\AccountController::class, 'get']);
Route::post('accounts', [\App\Http\Controllers\AccountController::class, 'store']);
Route::delete('accounts/{id}', [\App\Http\Controllers\AccountController::class, 'delete']);
Route::get('accounts/{accountId}/orders', [\App\Http\Controllers\OrderController::class, 'index']);
Route::get('orders/{orderId}/items', [\App\Http\Controllers\ItemController::class, 'getByOrderId']);
