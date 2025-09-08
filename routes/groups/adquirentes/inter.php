<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CallbackController;


Route::post('interbanking/callback', [CallbackController::class, 'callbackInter']);