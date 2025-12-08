<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use Illuminate\Http\Request;

class TradeController extends Controller
{
    public function index()
    {
        // Carga la relación 'account'
        return response()->json(Trade::with('account')->get());
    }

    public function store(Request $request)
    {
        // La validación asegura que la cuenta exista y que los datos sean correctos
        $validated = $request->validate([
            'account_id' => 'required|exists:account,id',
            'type' => 'required|in:BUY,SELL',
            'volume' => 'required|numeric|min:0.0001',
            'open_time' => 'required|date',
            'open_price' => 'required|numeric|min:0',
            'status' => 'required|in:open,closed', // Usamos el status por defecto 'open' en la migración
            'close_time' => 'nullable|date',
            'close_price' => 'nullable|numeric|min:0',
        ]);
        
        $trade = Trade::create($validated);

        return response()->json($trade, 201);
    }

    public function show(Trade $trade)
    {
        return response()->json($trade->load('account'));
    }

    public function update(Request $request, Trade $trade)
    {
        // Se utiliza principalmente para cerrar una operación (añadir close_time/close_price/status=closed)
        $validated = $request->validate([
            'close_time' => 'nullable|date',
            'close_price' => 'nullable|numeric|min:0',
            'status' => 'sometimes|in:open,closed',
        ]);

        $trade->update($validated);

        return response()->json($trade);
    }

    public function destroy(Trade $trade)
    {
        $trade->delete();
        return response()->json(null, 204);
    }
}