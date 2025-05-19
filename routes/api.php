<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CreditoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1/simulacao')->group(function() {

    Route::post('consultacredito',[CreditoController::class,'consultacredito']);  
    Route::post('simulacredito',[CreditoController::class,'simularCredito']);    

});
