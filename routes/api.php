<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SaqueController;
use App\Http\Controllers\Api\DepositController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('check.token.secret')->post('wallet/deposit/payment', [DepositController::class, 'makeDeposit']);
Route::middleware('check.token.secret')->post('pixout', [SaqueController::class, 'makePayment']);
Route::post('status', [DepositController::class, 'statusDeposito']);
