<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use App\Services\RiskEvaluationService;
use Illuminate\Http\Request;

class RiskEvaluationController extends Controller
{
    protected $riskService;

    public function __construct(RiskEvaluationService $riskService)
    {
        $this->riskService = $riskService;
    }

    public function evaluateTrade(Request $request, $tradeId)
    {
        $trade = Trade::with(['account'])->findOrFail($tradeId);
        
        $violations = $this->riskService->evaluateTrade($trade);

        if (empty($violations)) {
            return response()->json([
                'message' => 'Trade evaluado sin violaciones.',
                'violations' => []
            ], 200);
        }

        return response()->json([
            'message' => 'Se detectaron violaciones de reglas.',
            'violations' => $violations
        ], 200);
    }

    public function evaluateAccount(Request $request, $accountId)
    {
        $trades = Trade::where('account_id', $accountId)->get();
        $allViolations = [];

        foreach ($trades as $trade) {
            $violations = $this->riskService->evaluateTrade($trade);
            if (!empty($violations)) {
                $allViolations[$trade->id] = $violations;
            }
        }

        return response()->json([
            'message' => 'Evaluación de cuenta completada.',
            'total_trades' => $trades->count(),
            'violations' => $allViolations
        ], 200);
    }
}
