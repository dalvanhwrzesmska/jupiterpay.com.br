<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CallbackController;


Route::post('pradapay/callback/deposit', [CallbackController::class, 'callbackDeposit']);
Route::post('pradapay/callback/withdraw', [CallbackController::class, 'callbackWithdraw']);