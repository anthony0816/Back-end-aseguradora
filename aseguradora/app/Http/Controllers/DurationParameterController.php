<?php

namespace App\Http\Controllers;

use App\Models\DurationParameter;
use App\Models\Parameter;
use Illuminate\Http\Request;

class DurationParameterController extends Controller
{
    /**
     * READ: Muestra una lista de todos los parámetros de duración. (GET /api/duration-parameters)
     */
    public function index()
    {
        $durationParameters = DurationParameter::with(['parameter'])->get();
        return response()->json($durationParameters, 200);
    }

    /**
     * CREATE: Almacena un nuevo parámetro de duración en la base de datos. (POST /api/duration-parameters)
     */
    public function store(Request $request)
    {
        // Validación de los datos
        $validated = $request->validate([
            'parameter_id' => 'required|exists:parameters,id|unique:duration_parameters,parameter_id',
            'duration' => 'required|integer|min:1',
        ]);

        // Creación del registro en la base de datos
        $durationParameter = DurationParameter::create($validated);

        // Respuesta (Código 201: Creado)
        return response()->json([
            'message' => 'Parámetro de duración creado exitosamente.',
            'data' => $durationParameter
        ], 201);
    }

    /**
     * READ: Muestra un parámetro de duración específico. (GET /api/duration-parameters/{id})
     */
    public function show(string $id)
    {
        $durationParameter = DurationParameter::with(['parameter'])->findOrFail($id);
        return response()->json($durationParameter, 200);
    }

    /**
     * UPDATE: Actualiza un parámetro de duración específico en la base de datos. (PUT/PATCH /api/duration-parameters/{id})
     */
    public function update(Request $request, string $id)
    {
        // Busca el parámetro de duración
        $durationParameter = DurationParameter::findOrFail($id);

        $validated = $request->validate([
            'parameter_id' => 'sometimes|exists:parameters,id|unique:duration_parameters,parameter_id,' . $id . ',parameter_id',
            'duration' => 'sometimes|integer|min:1',
        ]);

        // Actualización y Respuesta (Código 200: OK)
        $durationParameter->update($validated);
        
        return response()->json([
            'message' => 'Parámetro de duración actualizado exitosamente.',
            'data' => $durationParameter
        ], 200);
    }

    /**
     * DELETE: Elimina un parámetro de duración específico de la base de datos. (DELETE /api/duration-parameters/{id})
     */
    public function destroy(string $id)
    {
        // Busca el parámetro de duración y lo elimina
        $durationParameter = DurationParameter::findOrFail($id);
        $durationParameter->delete();

        // Respuesta (Código 204: Sin Contenido)
        return response()->json(null, 204);
    }
}
