<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\City;
use App\Models\Tag;
use App\Models\EventAttendee;
use App\Models\Message;
use App\Models\Follow;
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
            ->fromActiveUsers()
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
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
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

            // Asignar tags si se proporcionaron
            if (!empty($validated['tags'])) {
                $event->tags()->attach($validated['tags']);
            }

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
        if ($event->status === 'pending') {
            if (!auth()->check() || (!auth()->user()->isAdmin() && auth()->id() !== $event->user_id)) {
                abort(403, 'No tienes permiso para ver este evento.');
            }
        }

        // Cargar eventos con sus relaciones
        $event->load(['category', 'user', 'city', 'attendees']);

        // Check if current user is registered
        $isRegistered = false;
        if (auth()->check()) {
            $isRegistered = EventAttendee::where('event_id', $event->id)
                ->where('user_id', auth()->id())
                ->exists();
        }

        // Verificar si el evento ha alcanzado el aforo máximo
        $isEventFull = false;
        if ($event->max_attendees !== null) {
            $currentAttendees = $event->attendees()->count();
            $isEventFull = $currentAttendees >= $event->max_attendees;
        }

        // Verificar si el evento ya ha pasado
        $isEventPast = $event->event_date < now()->startOfDay();

        // Verificar si el usuario actual sigue al organizador del evento
        $isFollowing = false;
        if (auth()->check() && $event->user) {
            $isFollowing = Follow::where('follower_id', auth()->id())
                ->where('followed_id', $event->user->id)
                ->exists();
        }

        // Obtener mensajes del foro del evento ordenados por fecha
        $messages = Message::where('event_id', $event->id)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        // Obtener eventos en la misma ciudad (excluyendo el actual)
        $otherEventsInCity = Event::where('city_id', $event->city_id)
            ->where('id', '!=', $event->id)
            ->where('event_date', '>=', now())
            ->where('status', 'approved')
            ->with(['category', 'city'])
            ->limit(10)
            ->get();

        return view('events.show', compact('event', 'otherEventsInCity', 'isRegistered', 'isEventFull', 'isEventPast', 'isFollowing', 'messages'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        // Permitir a admin y al creador del evento
        if (!auth()->check() || (!auth()->user()->isAdmin() && auth()->id() !== $event->user_id)) {
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
        if (!auth()->check() || (!auth()->user()->isAdmin() && auth()->id() !== $event->user_id)) {
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

    /**
     * Registrar asistencia de usuario a evento
     */
    public function register(Event $event)
    {
        // Comprobar si el usuario es el creador del evento
        if (auth()->id() === $event->user_id) {
            return redirect()->back()->with('error', 'No puedes apuntarte a tu propio evento.');
        }

        $existingAttendee = EventAttendee::where('event_id', $event->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingAttendee) {
            return redirect()->back()->with('error', 'Ya estás apuntado a este evento.');
        }

        // Comprobar si el evento tiene máximo de asistentes
        if ($event->max_attendees !== null) {
            $currentAttendees = EventAttendee::where('event_id', $event->id)
                ->count();

            if ($currentAttendees >= $event->max_attendees) {
                return redirect()->back()->with('error', 'El evento ha alcanzado el máximo de asistentes.');
            }
        }

        // Crear el registro de asistente al evento
        EventAttendee::create([
            'event_id' => $event->id,
            'user_id' => auth()->id(),
            'status' => 'confirmed',
        ]);

        return redirect()->back()->with('success', 'Te has apuntado al evento correctamente.');
    }

    /**
     * Cancelar asistencia a un evento
     */
    public function cancel(Event $event)
    {
        $attendee = EventAttendee::where('event_id', $event->id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$attendee) {
            return redirect()->back()->with('error', 'No estás apuntado a este evento.');
        }

        $attendee->delete();

        return redirect()->back()->with('success', 'Has cancelado tu asistencia al evento.');
    }

    /**
     * Mostrar los eventos del usuario (creados y asistidos)
     */
    public function myEvents()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userId = auth()->id();

        // Obtener eventos creados por el usuario
        $createdEvents = Event::where('user_id', $userId)
            ->with(['category', 'city'])
            ->orderBy('event_date', 'desc')
            ->get();

        // Obtener eventos asistidos por el usuario
        $attendedEventIds = EventAttendee::where('user_id', $userId)
            ->where('status', 'confirmed')
            ->pluck('event_id');

        $attendedEvents = Event::whereIn('id', $attendedEventIds)
            ->where('status', '!=', 'rejected')
            ->with(['category', 'city'])
            ->orderBy('event_date', 'desc')
            ->get();

        // Separar por pasados y próximos
        $now = now()->startOfDay();

        $createdUpcoming = $createdEvents->where('event_date', '>=', $now);
        $createdPast = $createdEvents->where('event_date', '<', $now);

        $attendedUpcoming = $attendedEvents->where('event_date', '>=', $now);
        $attendedPast = $attendedEvents->where('event_date', '<', $now);

        return view('events.my-events', compact(
            'createdUpcoming',
            'createdPast',
            'attendedUpcoming',
            'attendedPast'
        ));
    }

    /**
     * Mostrar eventos filtrados por categoría (following, city, interests)
     */
    public function filteredEvents($type)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        $events = collect();
        $title = '';
        $subtitle = '';

        switch ($type) {
            case 'following':
                // Eventos creados por las personas que el usuario sigue
                $followedUserIds = $user->following()->pluck('followed_id');
                if ($followedUserIds->isNotEmpty()) {
                    $events = Event::with(['category', 'user', 'city'])
                        ->whereIn('user_id', $followedUserIds)
                        ->where('status', 'approved')
                        ->where('event_date', '>=', now())
                        ->orderBy('event_date', 'asc')
                        ->paginate(12);
                }
                $title = __('home.for_you_title');
                $subtitle = __('home.for_you_subtitle');
                break;

            case 'city':
                // Eventos de la ciudad del usuario
                if ($user && $user->city_id) {
                    $events = Event::with(['category', 'user', 'city'])
                        ->where('city_id', $user->city_id)
                        ->where('status', 'approved')
                        ->where('event_date', '>=', now())
                        ->orderBy('event_date', 'asc')
                        ->paginate(12);
                }
                $cityName = $user && $user->city ? $user->city->name : 'tu ciudad';
                $title = __('home.city_title') . ' ' . $cityName;
                $subtitle = __('home.city_subtitle');
                break;

            case 'interests':
                // Eventos que tienen tags marcados como intereses por el usuario
                $userTagIds = $user->tags()->pluck('tags.id');
                if ($userTagIds->isNotEmpty()) {
                    $events = Event::with(['category', 'user', 'city', 'tags'])
                        ->where('status', 'approved')
                        ->where('event_date', '>=', now())
                        ->whereHas('tags', function ($query) use ($userTagIds) {
                            $query->whereIn('tags.id', $userTagIds);
                        })
                        ->orderBy('event_date', 'asc')
                        ->paginate(12);
                }
                $title = __('home.interests_title');
                $subtitle = __('home.interests_subtitle');
                break;

            default:
                return redirect()->route('home');
        }

        return view('events.filtered', compact('events', 'title', 'subtitle'));
    }
}
