<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\City;
use App\Models\Tag;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los eventos con sus relaciones básicas
        $events = Event::with(['category', 'user', 'city'])
            ->where('status', 'approved')
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->paginate(12);

        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $cities = City::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        return view('events.create', compact('categories', 'cities', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date|after_or_equal:today',
            'event_time' => 'required',
            'category_id' => 'required|exists:categories,id',
            'city_id' => 'required|exists:cities,id',
            'location' => 'required|string|max:255',
            'max_attendees' => 'nullable|integer|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/img/events'), $imageName);
            $coverImagePath = $imageName;
        }

        $event = Event::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'event_date' => $validated['event_date'],
            'event_time' => $validated['event_time'],
            'category_id' => $validated['category_id'],
            'city_id' => $validated['city_id'],
            'location' => $validated['location'],
            'max_attendees' => $validated['max_attendees'] ?? null,
            'cover_image' => $coverImagePath,
            'status' => 'pending', 
        ]);

        if (!empty($validated['tags'])) {
            $event->tags()->attach($validated['tags']);
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'Evento creado exitosamente. Está pendiente de aprobación.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        if ($event->status === 'pending') {
            if (!auth()->check() || (!auth()->user()->isAdmin() && auth()->id() !== $event->user_id)) {
                abort(403, 'No tienes permiso para ver este evento.');
            }
        }

        // Cargar eventos con sus relaciones
        $event->load(['category', 'user', 'city', 'attendees']);
        
        // Obtener eventos en la misma ciudad (excluyendo el actual)
        $otherEventsInCity = Event::where('city_id', $event->city_id)
            ->where('id', '!=', $event->id)
            ->where('event_date', '>=', now())
            ->where('status', 'approved')
            ->with(['category', 'city'])
            ->limit(10)
            ->get();
        
        return view('events.show', compact('event', 'otherEventsInCity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        // Permitir a admin y al creador del evento
        if (!auth()->check() || (!auth()->user()->isAdmin() && auth()->id() !== $event->user_id) || $event->status !== 'pending') {
            abort(403, 'No tienes permiso para editar este evento.');
        }

        $categories = Category::orderBy('name')->get();
        $cities = City::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        
        return view('events.edit', compact('event', 'categories', 'cities', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        // Permitir editar a admin o al creador del evento
        if (!auth()->check() || (!auth()->user()->isAdmin() && auth()->id() !== $event->user_id) || $event->status !== 'pending') {
            abort(403, 'No tienes permiso para editar este evento.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date|after_or_equal:today',
            'event_time' => 'required',
            'category_id' => 'required|exists:categories,id',
            'city_id' => 'required|exists:cities,id',
            'location' => 'required|string|max:255',
            'max_attendees' => 'nullable|integer|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // Cargar imagen
        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/img/events'), $imageName);
            $validated['cover_image'] = $imageName;
        }

        // Hacer update del evento
        $event->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'event_date' => $validated['event_date'],
            'event_time' => $validated['event_time'],
            'category_id' => $validated['category_id'],
            'city_id' => $validated['city_id'],
            'location' => $validated['location'],
            'max_attendees' => $validated['max_attendees'] ?? null,
            'status' => 'pending' // mantener pending para que un admin valide los nuevos cambios
        ]);

        // Si hay imagen nueva
        if (isset($validated['cover_image'])) {
            $event->cover_image = $validated['cover_image'];
            $event->save();
        }

        // Sincronizar tags
        if (!empty($validated['tags'])) {
            $event->tags()->sync($validated['tags']);
        } else {
            $event->tags()->detach();
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'Evento actualizado exitosamente. Sigue pendiente de aprobación.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        //
    }
}
