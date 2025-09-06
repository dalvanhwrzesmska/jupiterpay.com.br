<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CallbackController;


Route::post('jupiterpay/callback/deposit', [CallbackController::class, 'callbackDeposit']);
Route::post('jupiterpay/callback/withdraw', [CallbackController::class, 'callbackWithdraw']);