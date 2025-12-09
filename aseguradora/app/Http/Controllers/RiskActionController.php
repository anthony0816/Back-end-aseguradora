<?php

namespace App\Http\Controllers;

use App\Models\RiskAction;
use Illuminate\Http\Request;

class RiskActionController extends Controller
{
    public function index()
    {
        $actions = RiskAction::all();
        return response()->json($actions, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string|unique:risk_actions,slug',
        ]);

        $action = RiskAction::create($validated);
        return response()->json(['message' => 'Acción creada exitosamente.', 'data' => $action], 201);
    }

    public function show(string $id)
    {
        $action = RiskAction::with(['riskRules'])->findOrFail($id);
        return response()->json($action, 200);
    }

    public function update(Request $request, string $id)
    {
        $action = RiskAction::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|string',
            'slug' => 'sometimes|string|unique:risk_actions,slug,' . $id,
        ]);

        $action->update($validated);
        return response()->json(['message' => 'Acción actualizada exitosamente.', 'data' => $action], 200);
    }

    public function destroy(string $id)
    {
        $action = RiskAction::findOrFail($id);
        $action->delete();
        return response()->json(['message' => 'Acción eliminada exitosamente.'], 200);
    }
}
