<?php

namespace App\Http\Controllers;

use App\Models\RiskRuleSlug;
use Illuminate\Http\Request;

class RiskRuleSlugController extends Controller
{
    public function index()
    {
        $slugs = RiskRuleSlug::all();
        return response()->json($slugs, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:risk_rule_slugs,name',
            'slug' => 'required|string|unique:risk_rule_slugs,slug',
        ]);

        $slug = RiskRuleSlug::create($validated);
        return response()->json(['message' => 'Tipo de regla creado exitosamente.', 'data' => $slug], 201);
    }

    public function show(string $id)
    {
        $slug = RiskRuleSlug::with(['riskRules'])->findOrFail($id);
        return response()->json($slug, 200);
    }

    public function update(Request $request, string $id)
    {
        $slug = RiskRuleSlug::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|string|unique:risk_rule_slugs,name,' . $id,
            'slug' => 'sometimes|string|unique:risk_rule_slugs,slug,' . $id,
        ]);

        $slug->update($validated);
        return response()->json(['message' => 'Tipo de regla actualizado exitosamente.', 'data' => $slug], 200);
    }

    public function destroy(string $id)
    {
        $slug = RiskRuleSlug::findOrFail($id);
        $slug->delete();
        return response()->json(['message' => 'Tipo de regla eliminado exitosamente.'], 200);
    }
}
