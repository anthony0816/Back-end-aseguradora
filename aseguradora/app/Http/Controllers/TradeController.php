<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use Illuminate\Http\Request;

class TradeController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        if ($user->is_admin && $request->query('all') === 'true') {
            // Admin puede ver todos los trades con ?all=true
            $trades = Trade::with(['account'])->get();
        } else {
            // Usuario normal solo ve trades de sus cuentas
            $trades = Trade::with(['account'])
                ->whereHas('account', function ($query) use ($user) {
                    $query->where('owner_id', $user->id);
                })
                ->get();
        }
        
        return response()->json($trades, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'type' => 'required|in:BUY,SELL',
            'volume' => 'required|numeric|min:0',
            'open_time' => 'required|date',
            'close_time' => 'nullable|date|after:open_time',
            'open_price' => 'required|numeric|min:0',
            'close_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:open,closed',
        ]);

        $trade = Trade::create($validated);
        return response()->json(['message' => 'Trade creado exitosamente.', 'data' => $trade], 201);
    }

    public function show(string $id)
    {
        $trade = Trade::with(['account', 'incidents'])->findOrFail($id);
        return response()->json($trade, 200);
    }

    public function update(Request $request, string $id)
    {
        $trade = Trade::findOrFail($id);
        
        $validated = $request->validate([
            'account_id' => 'sometimes|exists:accounts,id',
            'type' => 'sometimes|in:BUY,SELL',
            'volume' => 'sometimes|numeric|min:0',
            'open_time' => 'sometimes|date',
            'close_time' => 'nullable|date|after:open_time',
            'open_price' => 'sometimes|numeric|min:0',
            'close_price' => 'nullable|numeric|min:0',
            'status' => 'sometimes|in:open,closed',
        ]);

        $trade->update($validated);
        return response()->json(['message' => 'Trade actualizado exitosamente.', 'data' => $trade], 200);
    }

    public function destroy(string $id)
    {
        $trade = Trade::findOrFail($id);
        $trade->delete();
        return response()->json(['message' => 'Trade eliminado exitosamente.'], 200);
    }
}
