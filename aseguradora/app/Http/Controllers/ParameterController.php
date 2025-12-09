<?php

namespace App\Http\Controllers;

use App\Models\Parameter;
use Illuminate\Http\Request;

class ParameterController extends Controller
{
    /**
     * READ: Muestra una lista de todos los parámetros. (GET /api/parameters)
     */
    public function index()
    {
        $parameters = Parameter::with([
            'durationParameter',
            'volumeTradeParameter',
            'timeRangeOperationParameter',
            'riskRule'
        ])->get();
        return response()->json($parameters, 200);
    }

    /**
     * CREATE: Almacena un nuevo parámetro en la base de datos. (POST /api/parameters)
     */
    public function store(Request $request)
    {
        // Creación del registro en la base de datos
        $parameter = Parameter::create();

        // Respuesta (Código 201: Creado)
        return response()->json([
            'message' => 'Parámetro creado exitosamente.',
            'data' => $parameter
        ], 201);
    }

    /**
     * READ: Muestra un parámetro específico. (GET /api/parameters/{id})
     */
    public function show(string $id)
    {
        $parameter = Parameter::with([
            'durationParameter',
            'volumeTradeParameter',
            'timeRangeOperationParameter',
            'riskRule'
        ])->findOrFail($id);
        return response()->json($parameter, 200);
    }

    /**
     * UPDATE: Actualiza un parámetro específico en la base de datos. (PUT/PATCH /api/parameters/{id})
     */
    public function update(Request $request, string $id)
    {
        // Busca el parámetro
        $parameter = Parameter::findOrFail($id);

        // Actualización y Respuesta (Código 200: OK)
        $parameter->update($request->all());
        
        return response()->json([
            'message' => 'Parámetro actualizado exitosamente.',
            'data' => $parameter
        ], 200);
    }

    /**
     * DELETE: Elimina un parámetro específico de la base de datos. (DELETE /api/parameters/{id})
     */
    public function destroy(string $id)
    {
        // Busca el parámetro y lo elimina
        $parameter = Parameter::findOrFail($id);
        $parameter->delete();

        // Respuesta (Código 204: Sin Contenido)
        return response()->json(null, 204);
    }
}
