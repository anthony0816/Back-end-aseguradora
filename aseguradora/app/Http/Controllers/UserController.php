<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request; // 🚨 USAMOS la clase base Request
use Illuminate\Support\Facades\Hash;
// 🚨 ELIMINADAS: use App\Http\Requests\UserStoreRequest; 
// 🚨 ELIMINADAS: use App\Http\Requests\UserUpdateRequest; 

class UserController extends Controller
{
    /**
     * Listar todos los usuarios.
     */
    public function index()
    {
        return response()->json(User::all());
    }

    //------------------------------------------------------------------

    /**
     * Crear un nuevo usuario. (La validación se mueve aquí)
     */
    public function store(Request $request) // 🚨 CAMBIADO: Usamos la Request base
    {
        // 🚨 VALIDACIÓN MANUAL (Reemplazando la UserStoreRequest)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:user,email',
            'password' => 'required|string|min:8|confirmed',
            'is_admin' => 'sometimes|boolean',
        ]);
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_admin' => $validated['is_admin'] ?? false, // Usamos '?? false' por si no se envía el campo
        ]);

        return response()->json($user, 201);
    }

    //------------------------------------------------------------------

    /**
     * Mostrar un usuario específico.
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    //------------------------------------------------------------------

    /**
     * Actualizar un usuario. (La validación se mueve aquí)
     */
    public function update(Request $request, User $user) // 🚨 CAMBIADO: Usamos la Request base
    {
        // 🚨 VALIDACIÓN MANUAL (Reemplazando la UserUpdateRequest)
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:user,email,' . $user->id,
            'password' => 'sometimes|string|min:8|confirmed',
            'is_admin' => 'sometimes|boolean',
        ]);

        $user->update($validated);

        return response()->json($user);
    }

    //------------------------------------------------------------------

    /**
     * Eliminar un usuario.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(null, 204);
    }
}