<?php

namespace App\Http\Controllers;

use App\Models\RiskRuleSlug;
use Illuminate\Http\Request;

class RiskRuleSlugController extends Controller
{
    public function index()
    {
        return response()->json(RiskRuleSlug::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:risk_rule_slug|max:255',
        ]);
        
        $slug = RiskRuleSlug::create($validated);
        return response()->json($slug, 201);
    }

    public function show(RiskRuleSlug $riskRuleSlug)
    {
        return response()->json($riskRuleSlug);
    }

    public function update(Request $request, RiskRuleSlug $riskRuleSlug)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:risk_rule_slug,slug,' . $riskRuleSlug->id,
        ]);

        $riskRuleSlug->update($validated);
        return response()->json($riskRuleSlug);
    }

    public function destroy(RiskRuleSlug $riskRuleSlug)
    {
        $riskRuleSlug->delete();
        return response()->json(null, 204);
    }
}