<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Event;
use App\Models\User;
use App\Models\Report;

class AdminController extends Controller
{
    /**
     * Mostrar el dashboard de administrador
     */
    public function dashboard()
    {
        // Obtener todos los eventos activos
        $activeEvents = Event::where('is_active', true)
            ->with(['category', 'city', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Obtener todos los usuarios registrados
        $users = User::with('role')
            ->orderBy('created_at', 'desc')
            ->get();

        // Obtener todos los reportes
        $reports = Report::with(['reporter', 'reportable'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Estadísticas
        $stats = [
            'total_events' => Event::count(),
            'active_events' => Event::where('is_active', true)->count(),
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_reports' => Report::count(),
            'pending_reports' => Report::where('status', 'pending')->count(),
        ];

        return view('admin.dashboard', compact('activeEvents', 'users', 'reports', 'stats'));
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
        $event->load(['category', 'city', 'user', 'attendees']);
        
        return view('admin.events.show', compact('event'));
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

        return back()->with('success', 'Reporte marcado como resuelto.');
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
}
