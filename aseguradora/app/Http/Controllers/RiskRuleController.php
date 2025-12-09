<?php

namespace App\Http\Controllers;

use App\Models\RiskRule;
use App\Models\Parameter;
use App\Models\DurationParameter;
use App\Models\ParameterVolumeTrade;
use App\Models\ParameterTimeRangeOperation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiskRuleController extends Controller
{
    public function index()
    {
        $rules = RiskRule::with(['creator', 'ruleType', 'parameter', 'actions'])->get();
        return response()->json($rules, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'created_by_user_id' => 'required|exists:users,id',
            'rule_type_id' => 'required|exists:risk_rule_slugs,id',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'severity' => 'required|in:Hard,Soft',
            'is_active' => 'boolean',
            'parameter_type' => 'required|in:duration,volume,time_range',
            'parameter_data' => 'required|array',
            'action_ids' => 'nullable|array',
            'action_ids.*' => 'exists:risk_actions,id',
        ]);

        DB::beginTransaction();
        try {
            $parameter = Parameter::create([]);
            
            switch ($validated['parameter_type']) {
                case 'duration':
                    DurationParameter::create([
                        'parameter_id' => $parameter->id,
                        'duration' => $validated['parameter_data']['duration'],
                    ]);
                    break;
                case 'volume':
                    ParameterVolumeTrade::create([
                        'parameter_id' => $parameter->id,
                        'min_factor' => $validated['parameter_data']['min_factor'],
                        'max_factor' => $validated['parameter_data']['max_factor'],
                        'lookback_trades' => $validated['parameter_data']['lookback_trades'],
                    ]);
                    break;
                case 'time_range':
                    ParameterTimeRangeOperation::create([
                        'parameter_id' => $parameter->id,
                        'time_window_minutes' => $validated['parameter_data']['time_window_minutes'],
                        'min_open_trades' => $validated['parameter_data']['min_open_trades'],
                        'max_open_trades' => $validated['parameter_data']['max_open_trades'],
                    ]);
                    break;
            }

            $rule = RiskRule::create([
                'created_by_user_id' => $validated['created_by_user_id'],
                'rule_type_id' => $validated['rule_type_id'],
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'severity' => $validated['severity'],
                'is_active' => $validated['is_active'] ?? true,
                'parameter_id' => $parameter->id,
            ]);

            if (isset($validated['action_ids'])) {
                $rule->actions()->attach($validated['action_ids']);
            }

            DB::commit();
            return response()->json(['message' => 'Regla de riesgo creada exitosamente.', 'data' => $rule->load(['parameter', 'actions'])], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al crear la regla.', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {
        $rule = RiskRule::with([
            'creator', 
            'ruleType', 
            'parameter.durationParameter',
            'parameter.volumeTradeParameter',
            'parameter.timeRangeOperationParameter',
            'actions',
            'incidents'
        ])->findOrFail($id);
        
        return response()->json($rule, 200);
    }

    public function update(Request $request, string $id)
    {
        $rule = RiskRule::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'description' => 'nullable|string',
            'severity' => 'sometimes|in:Hard,Soft',
            'is_active' => 'sometimes|boolean',
            'action_ids' => 'nullable|array',
            'action_ids.*' => 'exists:risk_actions,id',
        ]);

        $rule->update($validated);

        if (isset($validated['action_ids'])) {
            $rule->actions()->sync($validated['action_ids']);
        }

        return response()->json(['message' => 'Regla actualizada exitosamente.', 'data' => $rule->load(['actions'])], 200);
    }

    public function destroy(string $id)
    {
        $rule = RiskRule::findOrFail($id);
        $rule->delete();
        return response()->json(['message' => 'Regla eliminada exitosamente.'], 200);
    }
}
