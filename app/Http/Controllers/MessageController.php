<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Event;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Store nuevo mensaje en un evento
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'content' => 'required|string|max:1000',
        ]);

        // Verificar que el evento esté aprobado
        $event = Event::findOrFail($validated['event_id']);
        if ($event->status !== 'approved') {
            return redirect()->back()->with('error', 'No puedes enviar mensajes a eventos que no están aprobados.');
        }

        Message::create([
            'event_id' => $validated['event_id'],
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        return redirect()->back()->with('success', 'Mensaje enviado correctamente.');
    }
}
