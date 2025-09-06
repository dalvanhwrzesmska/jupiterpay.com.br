<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CallbackController;


Route::post('podpay/callback/deposit', [CallbackController::class, 'callbackDeposit']);
Route::post('podpay/callback/withdraw', [CallbackController::class, 'callbackWithdraw']);