<?php

namespace App\Http\Controllers;

use App\Models\ParameterTimeRangeOperation;
use Illuminate\Http\Request;

class ParameterTimeRangeOperationController extends Controller
{
    /**
     * READ: Muestra una lista de todos los parámetros de rango de tiempo. (GET /api/time-range-parameters)
     */
    public function index()
    {
        $timeRangeParameters = ParameterTimeRangeOperation::with(['parameter'])->get();
        return response()->json($timeRangeParameters, 200);
    }

    /**
     * CREATE: Almacena un nuevo parámetro de rango de tiempo en la base de datos. (POST /api/time-range-parameters)
     */
    public function store(Request $request)
    {
        // Validación de los datos
        $validated = $request->validate([
            'parameter_id' => 'required|exists:parameters,id|unique:parameter_time_range_operations,parameter_id',
            'time_window_minutes' => 'required|integer|min:1',
            'min_open_trades' => 'required|integer|min:0',
            'max_open_trades' => 'required|integer|min:0|gte:min_open_trades',
        ]);

        // Creación del registro en la base de datos
        $timeRangeParameter = ParameterTimeRangeOperation::create($validated);

        // Respuesta (Código 201: Creado)
        return response()->json([
            'message' => 'Parámetro de rango de tiempo creado exitosamente.',
            'data' => $timeRangeParameter
        ], 201);
    }

    /**
     * READ: Muestra un parámetro de rango de tiempo específico. (GET /api/time-range-parameters/{id})
     */
    public function show(string $id)
    {
        $timeRangeParameter = ParameterTimeRangeOperation::with(['parameter'])->findOrFail($id);
        return response()->json($timeRangeParameter, 200);
    }

    /**
     * UPDATE: Actualiza un parámetro de rango de tiempo específico en la base de datos. (PUT/PATCH /api/time-range-parameters/{id})
     */
    public function update(Request $request, string $id)
    {
        // Busca el parámetro de rango de tiempo
        $timeRangeParameter = ParameterTimeRangeOperation::findOrFail($id);

        $validated = $request->validate([
            'parameter_id' => 'sometimes|exists:parameters,id|unique:parameter_time_range_operations,parameter_id,' . $id . ',parameter_id',
            'time_window_minutes' => 'sometimes|integer|min:1',
            'min_open_trades' => 'sometimes|integer|min:0',
            'max_open_trades' => 'sometimes|integer|min:0|gte:min_open_trades',
        ]);

        // Actualización y Respuesta (Código 200: OK)
        $timeRangeParameter->update($validated);
        
        return response()->json([
            'message' => 'Parámetro de rango de tiempo actualizado exitosamente.',
            'data' => $timeRangeParameter
        ], 200);
    }

    /**
     * DELETE: Elimina un parámetro de rango de tiempo específico de la base de datos. (DELETE /api/time-range-parameters/{id})
     */
    public function destroy(string $id)
    {
        // Busca el parámetro de rango de tiempo y lo elimina
        $timeRangeParameter = ParameterTimeRangeOperation::findOrFail($id);
        $timeRangeParameter->delete();

        // Respuesta (Código 204: Sin Contenido)
        return response()->json(null, 204);
    }
}
