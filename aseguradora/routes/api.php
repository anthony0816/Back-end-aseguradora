<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PolizaController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\RiskRuleSlugController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Rutas API (TEMPORALMENTE DESPROTEGIDAS PARA PRUEBAS CRUD)
*/

// 🛑 RUTA DE PRUEBA DE SANCTUM (se debe comentar o eliminar si no se usa)
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::apiResource('polizas', PolizaController::class);


Route::apiResource('accounts', AccountController::class); 


Route::apiResource('users', UserController::class);


Route::apiResource('notifications', NotificationController::class);


Route::apiResource('trades', TradeController::class);

Route::apiResource('risk-rule-slugs', RiskRuleSlugController::class);


