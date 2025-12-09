<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use App\Models\Account;
use App\Services\RiskEvaluationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    protected $riskService;

    public function __construct(RiskEvaluationService $riskService)
    {
        $this->riskService = $riskService;
    }

    /**
     * Recibe trades desde sistemas externos (MT4/MT5, etc.)
     */
    public function receiveTrade(Request $request)
    {
        try {
            $validated = $request->validate([
                'account_login' => 'required|integer',
                'type' => 'required|in:BUY,SELL',
                'volume' => 'required|numeric|min:0',
                'open_time' => 'required|date',
                'close_time' => 'nullable|date',
                'open_price' => 'required|numeric|min:0',
                'close_price' => 'nullable|numeric|min:0',
                'status' => 'required|in:open,closed',
                'external_id' => 'nullable|string',
            ]);

            // Buscar la cuenta por login
            $account = Account::where('login', $validated['account_login'])->first();
            
            if (!$account) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cuenta no encontrada.',
                ], 404);
            }

            // Verificar si la cuenta está habilitada
            if ($account->status !== 'enable' || $account->trading_status !== 'enable') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cuenta deshabilitada.',
                    'account_status' => $account->status,
                    'trading_status' => $account->trading_status,
                ], 403);
            }

            // Crear el trade
            $trade = Trade::create([
                'account_id' => $account->id,
                'type' => $validated['type'],
                'volume' => $validated['volume'],
                'open_time' => $validated['open_time'],
                'close_time' => $validated['close_time'] ?? null,
                'open_price' => $validated['open_price'],
                'close_price' => $validated['close_price'] ?? null,
                'status' => $validated['status'],
            ]);

            // Evaluar automáticamente el trade
            $violations = $this->riskService->evaluateTrade($trade);

            Log::info('Trade recibido via webhook', [
                'trade_id' => $trade->id,
                'account_login' => $validated['account_login'],
                'violations' => count($violations),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Trade registrado exitosamente.',
                'trade_id' => $trade->id,
                'violations_detected' => count($violations),
                'violations' => $violations,
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error en webhook de trade', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el trade.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Actualiza un trade existente (cierre, modificación)
     */
    public function updateTrade(Request $request, $externalId)
    {
        try {
            $validated = $request->validate([
                'close_time' => 'nullable|date',
                'close_price' => 'nullable|numeric|min:0',
                'status' => 'nullable|in:open,closed',
            ]);

            $trade = Trade::where('id', $externalId)->first();
            
            if (!$trade) {
                return response()->json([
                    'success' => false,
                    'message' => 'Trade no encontrado.',
                ], 404);
            }

            $trade->update($validated);

            // Re-evaluar si el trade fue cerrado
            if (isset($validated['status']) && $validated['status'] === 'closed') {
                $violations = $this->riskService->evaluateTrade($trade);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Trade actualizado y evaluado.',
                    'violations_detected' => count($violations),
                    'violations' => $violations,
                ], 200);
            }

            return response()->json([
                'success' => true,
                'message' => 'Trade actualizado exitosamente.',
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error actualizando trade via webhook', [
                'external_id' => $externalId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el trade.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Endpoint de health check para verificar que el webhook está activo
     */
    public function healthCheck()
    {
        return response()->json([
            'status' => 'ok',
            'service' => 'Risk Control Webhook',
            'timestamp' => now()->toIso8601String(),
        ], 200);
    }
}
