<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Event;
use App\Models\User;
use App\Models\Report;
use App\Models\Category;
use App\Models\Tag;

class AdminController extends Controller
{
    /**
     * Mostrar el dashboard de administrador
     */
    public function dashboard()
    {
        // Obtener eventos pendientes de aprobación
        $pendingEvents = Event::where('status', 'pending')
            ->with(['category', 'city', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Obtener eventos activos
        $activeEvents = Event::where('is_active', true)
            ->with(['category', 'city', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Obtener todos los usuarios registrados
        $users = User::with('role')
            ->orderBy('created_at', 'desc')
            ->get();

        // Obtener reportes pendientes
        $pendingReports = Report::where('status', 'pending')
            ->with(['reporter', 'reportable'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Obtener todos los reportes
        $reports = Report::with(['reporter', 'reportable'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Estadísticas actualizadas con datos reales
        $stats = [
            'pending_events' => Event::where('status', 'pending')->count(),
            'pending_reports' => Report::where('status', 'pending')->count(),
            'active_events' => Event::where('is_active', true)->count(),
            'active_users' => User::where('is_active', true)->count(),
        ];

        return view('admin.dashboard', compact('pendingEvents', 'activeEvents', 'users', 'pendingReports', 'reports', 'stats'));
    }

    /**
     * Mostrar todos los eventos
     */
    public function events(Request $request)
    {
        $query = Event::with(['category', 'city', 'user']);

        // Filtrar por estado (activo/inactivo)
        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Filtrar por categoría
        if ($request->has('category') && $request->category !== '') {
            $query->where('category_id', $request->category);
        }

        // Filtrar por búsqueda (título o descripción)
        if ($request->has('search') && $request->search !== '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('description', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('location', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $events = $query->orderBy('created_at', 'desc')->paginate(20);

        // Mantener los parámetros de filtrado en la paginación
        $events->appends($request->query());

        return view('admin.events', compact('events'));
    }

    /**
     * Mostrar detalles de un evento para aprobación
     */
    public function showEvent(Event $event)
    {
        // Cargar todas las relaciones del evento
        $event->load(['category', 'city', 'user', 'attendees', 'tags']);

        // Obtener todas las ciudades para el autocomplete
        $cities = \App\Models\City::all(['id', 'name']);

        // Obtener todas las categorías para el selector
        $categories = Category::all(['id', 'name']);

        // Obtener todos los tags para el selector
        $tags = Tag::all(['id', 'name']);

        return view('admin.events.show', compact('event', 'cities', 'categories', 'tags'));
    }

    /**
     * Aprobar un evento
     */
    public function approveEvent(Event $event)
    {
        $event->status = 'approved';
        $event->is_active = true;
        $event->save();

        return redirect()->route('admin.events')
            ->with('success', 'Evento "' . $event->title . '" ha sido aprobado exitosamente.');
    }

    /**
     * Rechazar un evento
     */
    public function rejectEvent(Request $request, Event $event)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10|max:500'
        ]);

        $event->status = 'rejected';
        $event->is_active = false;
        $event->rejection_reason = $validated['rejection_reason'];
        $event->save();

        return redirect()->route('admin.events')
            ->with('success', 'Evento "' . $event->title . '" ha sido rechazado.');
    }

    /**
     * Actualizar un evento
     */
    public function updateEvent(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'event_time' => 'required',
            'max_attendees' => 'nullable|integer|min:1',
            'location' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'city_id' => 'required|exists:cities,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // Manejar la imagen de portada si se subió
        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/img/events'), $imageName);
            $validated['cover_image'] = $imageName;
        }

        $event->update($validated);

        // Sincronizar tags si hay alguno seleccionado
        if ($request->has('tags')) {
            $event->tags()->sync($request->tags ?? []);
        }

        return redirect()->route('admin.events.show', $event)
            ->with('success', 'Evento "' . $event->title . '" ha sido actualizado exitosamente.');
    }

    /**
     * Mostrar todos los usuarios
     */
    public function users(Request $request)
    {
        $query = User::with('role');

        // Filtrar por rol
        if ($request->has('role') && $request->role !== '') {
            $query->whereHas('role', function($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Filtrar por estado (activo/inactivo)
        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'active') {
                $query->where('is_active', 1);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', 0);
            }
        }

        // Filtrar por búsqueda (username, nombre, email)
        if ($request->has('search') && $request->search !== '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('username', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('name', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('surname', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('email', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        // Mantener los parámetros de filtrado en la paginación
        $users->appends($request->query());

        return view('admin.users', compact('users'));
    }

    /**
     * Mostrar todos los reportes
     */
    public function reports()
    {
        $reports = Report::with(['reporter', 'reportable'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.reports', compact('reports'));
    }

    /**
     * Activar/desactivar un evento
     */
    public function toggleEvent(Event $event)
    {
        $event->is_active = !$event->is_active;
        $event->save();

        $status = $event->is_active ? 'activado' : 'desactivado';
        return back()->with('success', "Evento {$status} correctamente.");
    }

    /**
     * Activar/desactivar un usuario
     */
    public function toggleUser(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activado' : 'desactivado';
        return back()->with('success', "Usuario {$status} correctamente.");
    }

    /**
     * Marcar un reporte como resuelto
     */
    public function resolveReport(Report $report)
    {
        $report->status = 'resolved';
        $report->save();

        // Si el reporte es sobre un evento y el evento está aprobado, cambiarlo a rechazado
        if ($report->reportable_type === 'App\Models\Event' && $report->reportable) {
            $event = $report->reportable;
            if ($event->status === 'approved') {
                $event->status = 'rejected';
                $event->rejection_reason = 'Evento rechazado debido a un reporte aceptado: ' . $report->reason;
                $event->save();
            }
        }

        return back()->with('success', 'Reporte marcado como resuelto y evento rechazado si era aplicable.');
    }

    /**
     * Rechazar un reporte
     */
    public function rejectReport(Report $report)
    {
        $report->status = 'reviewed';
        $report->save();

        return back()->with('success', 'Reporte rechazado.');
    }

    /**
     * Mostrar todas las categorías
     */
    public function categories(Request $request)
    {
        $categories = Category::with('events')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.categories', compact('categories'));
    }

    /**
     * Mostrar formulario para crear categoría
     */
    public function createCategory()
    {
        return view('admin.categories.create');
    }

    /**
     * Guardar nueva categoría
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:50',
        ]);

        Category::create($validated);

        return redirect()->route('admin.categories')
            ->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Mostrar formulario para editar categoría
     */
    public function editCategory(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Actualizar categoría
     */
    public function updateCategory(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:50',
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories')
            ->with('success', 'Categoría actualizada exitosamente.');
    }

    /**
     * Eliminar categoría
     */
    public function deleteCategory(Category $category)
    {
        // Verificar si hay eventos asociados
        if ($category->events()->count() > 0) {
            return back()->with('error', 'No se puede eliminar la categoría porque tiene eventos asociados.');
        }

        $category->delete();

        return redirect()->route('admin.categories')
            ->with('success', 'Categoría eliminada exitosamente.');
    }

    /**
     * Mostrar todos los tags
     */
    public function tags(Request $request)
    {
        $query = Tag::with('events');

        // Filtrar por búsqueda
        if ($request->has('search') && $request->search !== '') {
            $searchTerm = $request->search;
            $query->where('name', 'LIKE', '%' . $searchTerm . '%');
        }

        $tags = $query->orderBy('name')->paginate(20);
        $tags->appends($request->query());

        return view('admin.tags', compact('tags'));
    }

    /**
     * Mostrar formulario para crear tag
     */
    public function createTag()
    {
        return view('admin.tags.create');
    }

    /**
     * Guardar nuevo tag
     */
    public function storeTag(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:tags,name',
        ]);

        Tag::create($validated);

        return redirect()->route('admin.tags')
            ->with('success', 'Tag creado exitosamente.');
    }

    /**
     * Mostrar formulario para editar tag
     */
    public function editTag(Tag $tag)
    {
        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Actualizar tag
     */
    public function updateTag(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:tags,name,' . $tag->id,
        ]);

        $tag->update($validated);

        return redirect()->route('admin.tags')
            ->with('success', 'Tag actualizado exitosamente.');
    }

    /**
     * Eliminar tag
     */
    public function deleteTag(Tag $tag)
    {
        // Eliminar relaciones con eventos antes de eliminar el tag
        $tag->events()->detach();

        $tag->delete();

        return redirect()->route('admin.tags')
            ->with('success', 'Tag eliminado exitosamente.');
    }
}
