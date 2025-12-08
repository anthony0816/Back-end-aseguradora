<?php

namespace App\Http\Controllers;
use App\Models\Poliza;
use Illuminate\Http\Request;

class PolizaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    // 1. Validación de los datos
    $validated = $request->validate([ // <-- ¡Faltaba la función validate y la variable!
        'numero_poliza' => 'required|string|max:255|unique:polizas,numero_poliza',
        'cliente' => 'required|string|max:255',
        'monto' => 'required|numeric',
        'fecha_inicio' => 'required|date',
        'fecha_fin' => 'required|date|after:fecha_inicio',
    ]); // <-- ¡Y faltaba el paréntesis de cierre!

    // 2. Creación del registro en la base de datos
    $poliza = Poliza::create($validated);

    // 3. Respuesta de la API
    return response()->json([
        'message' => 'Póliza creada exitosamente.',
        'data' => $poliza
    ], 201);

        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
