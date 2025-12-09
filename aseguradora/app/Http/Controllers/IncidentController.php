<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function index()
    {
        $incidents = Incident::with(['account', 'riskRule', 'trade'])->get();
        return response()->json($incidents, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'risk_rule_id' => 'required|exists:risk_rules,id',
            'trade_id' => 'nullable|exists:trades,id',
            'count' => 'integer|min:1',
            'triggered_value' => 'nullable|string',
            'is_executed' => 'boolean',
        ]);

        $incident = Incident::create($validated);
        return response()->json(['message' => 'Incidente creado exitosamente.', 'data' => $incident], 201);
    }

    public function show(string $id)
    {
        $incident = Incident::with(['account', 'riskRule', 'trade'])->findOrFail($id);
        return response()->json($incident, 200);
    }

    public function update(Request $request, string $id)
    {
        $incident = Incident::findOrFail($id);
        
        $validated = $request->validate([
            'count' => 'sometimes|integer|min:1',
            'triggered_value' => 'nullable|string',
            'is_executed' => 'sometimes|boolean',
        ]);

        $incident->update($validated);
        return response()->json(['message' => 'Incidente actualizado exitosamente.', 'data' => $incident], 200);
    }

    public function destroy(string $id)
    {
        $incident = Incident::findOrFail($id);
        $incident->delete();
        return response()->json(['message' => 'Incidente eliminado exitosamente.'], 200);
    }
}
