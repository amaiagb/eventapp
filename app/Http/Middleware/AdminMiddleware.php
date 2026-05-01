<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Verificar que el usuario sea administrador
        if (Auth::user()->role && Auth::user()->role->name === 'admin') {
            return $next($request);
        }

        // Si no es admin, redirigir al home con mensaje de error
        return redirect()->route('home')->with('error', 'No tienes permisos de administrador.');
    }
}
