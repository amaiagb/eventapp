<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Mostrar el perfil del usuario.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        // Verificar que el usuario esté activo
        if ($user->is_active === null || $user->is_active === false || $user->is_active === 0) {
            abort(404);
        }

        // Obtener eventos públicos (solo eventos aprobados)
        $events = $user->events()
            ->where('status', 'approved')
            ->orderBy('event_date', 'asc')
            ->paginate(6);

        // Verificar si el usuario actual sigue a este usuario
        $isFollowing = false;
        if (Auth::check()) {
            $isFollowing = Follow::where('follower_id', Auth::id())
                ->where('followed_id', $user->id)
                ->exists();
        }

        // Obtener contador de seguidores y seguidos
        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();

        return view('users.show', compact('user', 'events', 'isFollowing', 'followersCount', 'followingCount'));
    }

    /**
     * Toggle seguir/dejar de seguir usuario.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function toggleFollow(User $user)
    {
        if (!Auth::check()) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Debes estar autenticado']);
            }
            return redirect()->route('login');
        }

        if (Auth::id() === $user->id) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'No puedes seguirte a ti mismo']);
            }
            return redirect()->back()->with('error', 'No puedes seguirte a ti mismo.');
        }

        $existingFollow = Follow::where('follower_id', Auth::id())
            ->where('followed_id', $user->id)
            ->first();

        if ($existingFollow) {
            // Dejar de seguir
            $existingFollow->delete();
            $message = 'Has dejado de seguir a ' . $user->name;
            
            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => $message]);
            }
            return redirect()->back()->with('success', $message);
        } else {
            // Seguir
            Follow::create([
                'follower_id' => Auth::id(),
                'followed_id' => $user->id,
            ]);
            $message = 'Ahora sigues a ' . $user->name;
            
            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => $message]);
            }
            return redirect()->back()->with('success', $message);
        }
    }
}
