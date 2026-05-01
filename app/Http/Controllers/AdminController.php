<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
}
