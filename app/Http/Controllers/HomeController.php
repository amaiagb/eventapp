<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Follow;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Sin middleware auth para permitir acceso a visitantes
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();

        // Si no hay usuario autenticado, mostrar solo eventos genéricos
        if (!$user) {
            $forYouEvents = collect();
            $cityEvents = collect();
            $interestEvents = collect();
            $userCityName = 'tu ciudad';

            $genericEvents = Event::with(['category', 'user', 'city'])
                ->where('status', 'approved')
                ->where('event_date', '>=', now())
                ->fromActiveUsers()
                ->orderBy('event_date', 'asc')
                ->take(7)
                ->get();

            return view('home', compact(
                'forYouEvents',
                'cityEvents',
                'interestEvents',
                'genericEvents',
                'userCityName'
            ));
        }

        // Para usuarios autenticados, mostrar secciones personalizadas
        // Especialmente para ti: eventos creados por las personas que sigo
        $forYouEvents = collect();
        $followedUserIds = $user->following()->pluck('followed_id');
        if ($followedUserIds->isNotEmpty()) {
            $forYouEvents = Event::with(['category', 'user', 'city'])
                ->whereIn('user_id', $followedUserIds)
                ->where('status', 'approved')
                ->where('event_date', '>=', now())
                ->fromActiveUsers()
                ->orderBy('event_date', 'asc')
                ->take(7)
                ->get();
        }

        // En tu ciudad: eventos de la misma ciudad que el usuario
        $cityEvents = collect();
        if ($user->city_id) {
            $cityEvents = Event::with(['category', 'user', 'city'])
                ->where('city_id', $user->city_id)
                ->where('status', 'approved')
                ->where('event_date', '>=', now())
                ->fromActiveUsers()
                ->orderBy('event_date', 'asc')
                ->take(7)
                ->get();
        }

        // Según tus intereses: eventos que tienen tags marcados como intereses por el usuario
        $interestEvents = collect();
        $userTagIds = $user->tags()->pluck('tags.id');
        if ($userTagIds->isNotEmpty()) {
            $interestEvents = Event::with(['category', 'user', 'city', 'tags'])
                ->where('status', 'approved')
                ->where('event_date', '>=', now())
                ->whereHas('tags', function ($query) use ($userTagIds) {
                    $query->whereIn('tags.id', $userTagIds);
                })
                ->fromActiveUsers()
                ->orderBy('event_date', 'asc')
                ->take(7)
                ->get();
        }

        // Eventos genéricos: eventos destacados/recientes para cuando no hay eventos personalizados
        $genericEvents = Event::with(['category', 'user', 'city'])
            ->where('status', 'approved')
            ->where('event_date', '>=', now())
            ->fromActiveUsers()
            ->orderBy('event_date', 'asc')
            ->take(7)
            ->get();

        // Obtener nombre de la ciudad del usuario
        $userCityName = $user->city ? $user->city->name : 'tu ciudad';

        return view('home', compact(
            'forYouEvents',
            'cityEvents',
            'interestEvents',
            'genericEvents',
            'userCityName'
        ));
    }
}
