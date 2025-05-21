<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CreditoController;
use App\Http\Controllers\Api\UserController;

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/

Route::prefix('v1/simulacao')->group(function() {

    Route::post('/login',[UserController::class,'login']);
    Route::get('/logout',[UserController::class,'logout']);

    Route::post('/consultacredito',[CreditoController::class,'consultacredito'])->middleware('auth:sanctum');  
    Route::post('/simulacredito',[CreditoController::class,'simularCredito'])->middleware('auth:sanctum');   
    Route::post('/melhoresofertas',[CreditoController::class,'consultarMelhoresOfertas'])->middleware('auth:sanctum');    

});
