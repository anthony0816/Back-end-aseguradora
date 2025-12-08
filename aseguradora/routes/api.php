<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PolizaController;
use App\Http\Controllers\AccountController;

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



// 2. Ruta de Recursos API para Pólizas
// Esto crea las 5 rutas RESTful estándar (GET, POST, PUT, DELETE)
Route::apiResource('polizas', PolizaController::class);



// NUEVO: Rutas para Cuentas
Route::apiResource('accounts', AccountController::class); 