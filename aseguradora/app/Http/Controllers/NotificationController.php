<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        // ✅ CORREGIDO: Usamos select() para cargar solo el 'id' y 'name' del usuario
        return response()->json(
            Notification::with([
                'user' => function ($query) {
                    $query->select('id', 'name'); 
                }
            ])->get()
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:user,id',
            'mensaje' => 'required|string|max:255',
            'metadata' => 'nullable|json',
        ]);
        
        $notification = Notification::create($validated);

        return response()->json($notification, 201);
    }

    public function show(Notification $notification)
    {
        // ✅ CORREGIDO: Usamos load() y select() para limitar los campos del usuario
        return response()->json(
            $notification->load([
                'user' => function ($query) {
                    $query->select('id', 'name');
                }
            ])
        );
    }

    public function update(Request $request, Notification $notification)
    {
        $validated = $request->validate([
            'mensaje' => 'sometimes|string|max:255',
            'metadata' => 'nullable|json',
        ]);

        $notification->update($validated);

        return response()->json($notification);
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();
        return response()->json(null, 204);
    }
}