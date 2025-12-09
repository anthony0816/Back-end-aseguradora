<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with(['user'])->get();
        return response()->json($notifications, 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'metadata' => 'nullable|json',
            'mensaje' => 'required|string',
        ]);

        $notification = Notification::create($validated);
        return response()->json(['message' => 'Notificación creada exitosamente.', 'data' => $notification], 201);
    }

    public function show(string $id)
    {
        $notification = Notification::with(['user'])->findOrFail($id);
        return response()->json($notification, 200);
    }

    public function update(Request $request, string $id)
    {
        $notification = Notification::findOrFail($id);
        
        $validated = $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'metadata' => 'nullable|json',
            'mensaje' => 'sometimes|string',
        ]);

        $notification->update($validated);
        return response()->json(['message' => 'Notificación actualizada exitosamente.', 'data' => $notification], 200);
    }

    public function destroy(string $id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();
        return response()->json(['message' => 'Notificación eliminada exitosamente.'], 200);
    }
}
