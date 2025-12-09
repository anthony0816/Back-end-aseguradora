<?php

namespace App\Http\Controllers;

use App\Models\ParameterVolumeTrade;
use Illuminate\Http\Request;

class ParameterVolumeTradeController extends Controller
{
    /**
     * READ: Muestra una lista de todos los parámetros de volumen. (GET /api/volume-parameters)
     */
    public function index()
    {
        $volumeParameters = ParameterVolumeTrade::with(['parameter'])->get();
        return response()->json($volumeParameters, 200);
    }

    /**
     * CREATE: Almacena un nuevo parámetro de volumen en la base de datos. (POST /api/volume-parameters)
     */
    public function store(Request $request)
    {
        // Validación de los datos
        $validated = $request->validate([
            'parameter_id' => 'required|exists:parameters,id|unique:parameter_volume_trades,parameter_id',
            'min_factor' => 'required|numeric|min:0',
            'max_factor' => 'required|numeric|min:0|gte:min_factor',
            'lookback_trades' => 'required|integer|min:1',
        ]);

        // Creación del registro en la base de datos
        $volumeParameter = ParameterVolumeTrade::create($validated);

        // Respuesta (Código 201: Creado)
        return response()->json([
            'message' => 'Parámetro de volumen creado exitosamente.',
            'data' => $volumeParameter
        ], 201);
    }

    /**
     * READ: Muestra un parámetro de volumen específico. (GET /api/volume-parameters/{id})
     */
    public function show(string $id)
    {
        $volumeParameter = ParameterVolumeTrade::with(['parameter'])->findOrFail($id);
        return response()->json($volumeParameter, 200);
    }

    /**
     * UPDATE: Actualiza un parámetro de volumen específico en la base de datos. (PUT/PATCH /api/volume-parameters/{id})
     */
    public function update(Request $request, string $id)
    {
        // Busca el parámetro de volumen
        $volumeParameter = ParameterVolumeTrade::findOrFail($id);

        $validated = $request->validate([
            'parameter_id' => 'sometimes|exists:parameters,id|unique:parameter_volume_trades,parameter_id,' . $id . ',parameter_id',
            'min_factor' => 'sometimes|numeric|min:0',
            'max_factor' => 'sometimes|numeric|min:0|gte:min_factor',
            'lookback_trades' => 'sometimes|integer|min:1',
        ]);

        // Actualización y Respuesta (Código 200: OK)
        $volumeParameter->update($validated);
        
        return response()->json([
            'message' => 'Parámetro de volumen actualizado exitosamente.',
            'data' => $volumeParameter
        ], 200);
    }

    /**
     * DELETE: Elimina un parámetro de volumen específico de la base de datos. (DELETE /api/volume-parameters/{id})
     */
    public function destroy(string $id)
    {
        // Busca el parámetro de volumen y lo elimina
        $volumeParameter = ParameterVolumeTrade::findOrFail($id);
        $volumeParameter->delete();

        // Respuesta (Código 204: Sin Contenido)
        return response()->json(null, 204);
    }
}
