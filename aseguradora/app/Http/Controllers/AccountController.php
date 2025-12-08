<?php

namespace App\Http\Controllers;

use App\Models\Account; // Importar el Modelo Account
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * READ: Muestra una lista de todas las cuentas. (GET /api/accounts)
     */
    public function index()
    {
        // Obtiene todas las cuentas de la base de datos
        $accounts = Account::all();
        
        return response()->json($accounts, 200);
    }

    /**
     * CREATE: Almacena una nueva cuenta en la base de datos. (POST /api/accounts)
     */
    public function store(Request $request)
    {
        // 1. Validación de los datos
        $validated = $request->validate([
            // 'login' debe ser un número entero grande, único en la tabla 'accounts'.
            'login' => 'required|integer|min:1|unique:accounts,login',
            
            // 'trading_status' y 'status' deben ser 'enable' o 'disable'
            'trading_status' => 'required|in:enable,disable',
            'status' => 'required|in:enable,disable',
        ]);

        // 2. Creación del registro en la base de datos
        $account = Account::create($validated);

        // 3. Respuesta (Código 201: Creado)
        return response()->json([
            'message' => 'Cuenta creada exitosamente.',
            'data' => $account
        ], 201);
    }

    /**
     * READ: Muestra una cuenta específica. (GET /api/accounts/{id})
     */
    public function show(string $id)
    {
        // Busca la cuenta por ID, si no la encuentra, devuelve un error 404
        $account = Account::findOrFail($id);

        return response()->json($account, 200);
    }

    /**
     * UPDATE: Actualiza una cuenta específica en la base de datos. (PUT/PATCH /api/accounts/{id})
     */
    public function update(Request $request, string $id)
    {
        // 1. Busca la cuenta
        $account = Account::findOrFail($id);

        // 2. Validación: 'login' debe ser único, IGNORANDO el login de la cuenta actual ($account->id)
        $validated = $request->validate([
            'login' => 'sometimes|required|integer|min:1|unique:accounts,login,' . $account->id,
            'trading_status' => 'sometimes|required|in:enable,disable',
            'status' => 'sometimes|required|in:enable,disable',
        ]);

        // 3. Actualización y Respuesta (Código 200: OK)
        $account->update($validated);
        
        return response()->json([
            'message' => 'Cuenta actualizada exitosamente.',
            'data' => $account
        ], 200);
    }

    /**
     * DELETE: Elimina una cuenta específica de la base de datos. (DELETE /api/accounts/{id})
     */
    public function destroy(string $id)
    {
        // 1. Busca la cuenta y la elimina
        $account = Account::findOrFail($id);
        $account->delete();

        // 2. Respuesta (Código 204: Sin Contenido)
        return response()->json(null, 204);
    }
}