<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $cities = \Nnjeim\World\Models\City::with('country')->orderBy('name')->get();
        return view('events.create', compact('categories', 'cities'));
    }

    /**
     * Display events created by the current user.
     */
    public function myEvents()
    {
        $events = Event::with(['category', 'city'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('events.my-events', compact('events'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'category_id' => 'required|exists:categories,id',
            'city_id' => 'required|exists:cities,id',
            'event_date' => 'required|date|after:today',
            'event_time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'max_attendees' => 'nullable|integer|min:1|max:10000',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            // Crear el evento
            $event = new Event();
            $event->user_id = Auth::id();
            $event->category_id = $validated['category_id'];
            $event->city_id = $validated['city_id'];
            $event->title = $validated['title'];
            $event->description = $validated['description'];
            $event->event_date = $validated['event_date'];
            $event->event_time = $validated['event_time'];
            $event->location = $validated['location'];
            $event->max_attendees = $validated['max_attendees'] ?? null;
            $event->status = 'pending'; // Los eventos nuevos quedan pendientes de aprobación

            // Manejar la imagen de portada si se subió
            if ($request->hasFile('cover_image')) {
                $image = $request->file('cover_image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('storage/img/events'), $imageName);
                $event->cover_image = $imageName;
            }

            // Guardar el evento
            $event->save();

            // Redirigir a Mis Eventos con mensaje de éxito
            return redirect()->route('my.events')->with('success', 'Evento creado exitosamente. Está pendiente de aprobación.');

        } catch (\Exception $e) {
            // En caso de error, redirigir de vuelta con mensaje de error
            return redirect()->back()
                ->withInput()
                ->with('error', 'Hubo un error al crear el evento: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        // Cargar eventos con sus relaciones
        $event->load(['category', 'user', 'city', 'attendees']);
        
        // Obtener eventos en la misma ciudad (excluyendo el actual)
        $otherEventsInCity = Event::where('city_id', $event->city_id)
            ->where('id', '!=', $event->id)
            ->where('event_date', '>=', now())
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        //
    }
}
