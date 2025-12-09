<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PolizaController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\RiskRuleSlugController;
use App\Http\Controllers\RiskRuleController;
use App\Http\Controllers\RiskActionController;
use App\Http\Controllers\IncidentController;
use App\Http\Controllers\RiskEvaluationController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\DurationParameterController;
use App\Http\Controllers\ParameterVolumeTradeController;
use App\Http\Controllers\ParameterTimeRangeOperationController;

/*
|--------------------------------------------------------------------------
| API Routes - Risk Control System
|--------------------------------------------------------------------------
*/

// Rutas Públicas (sin autenticación)
Route::post('register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('login', [App\Http\Controllers\AuthController::class, 'login']);

// Rutas de Webhook (públicas para integración externa)
Route::post('webhook/trade', [App\Http\Controllers\WebhookController::class, 'receiveTrade']);
Route::put('webhook/trade/{externalId}', [App\Http\Controllers\WebhookController::class, 'updateTrade']);
Route::get('webhook/health', [App\Http\Controllers\WebhookController::class, 'healthCheck']);

// Rutas Protegidas (requieren autenticación)
Route::middleware('auth:sanctum')->group(function () {
    // Autenticación
    Route::post('logout', [App\Http\Controllers\AuthController::class, 'logout']);
    Route::get('me', [App\Http\Controllers\AuthController::class, 'me']);

    // Recursos API
    Route::apiResource('users', UserController::class);
    Route::apiResource('accounts', AccountController::class);
    Route::apiResource('notifications', NotificationController::class);
    Route::apiResource('trades', TradeController::class);
    Route::apiResource('risk-rule-slugs', RiskRuleSlugController::class);
    Route::apiResource('risk-rules', RiskRuleController::class);
    Route::apiResource('risk-actions', RiskActionController::class);
    Route::apiResource('incidents', IncidentController::class);
    
    // Parámetros y sus tipos
    Route::apiResource('parameters', ParameterController::class);
    Route::apiResource('duration-parameters', DurationParameterController::class);
    Route::apiResource('volume-parameters', ParameterVolumeTradeController::class);
    Route::apiResource('time-range-parameters', ParameterTimeRangeOperationController::class);

    // Evaluación de riesgo
    Route::post('risk-evaluation/trade/{tradeId}', [RiskEvaluationController::class, 'evaluateTrade']);
    Route::post('risk-evaluation/account/{accountId}', [RiskEvaluationController::class, 'evaluateAccount']);

    // Dashboard y Estadísticas
    Route::get('dashboard/stats', [App\Http\Controllers\DashboardController::class, 'stats']);
    Route::get('dashboard/recent-incidents', [App\Http\Controllers\DashboardController::class, 'recentIncidents']);
    Route::get('dashboard/account-risk-profile/{accountId}', [App\Http\Controllers\DashboardController::class, 'accountRiskProfile']);

    // Ruta legacy de pólizas
    Route::apiResource('polizas', PolizaController::class);
});

// Ruta legacy de pólizas (mantener si es necesario)
Route::apiResource('polizas', PolizaController::class); 