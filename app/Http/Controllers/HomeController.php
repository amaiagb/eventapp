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
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        
        // Especialmente para ti: eventos creados por las personas que sigo
        $forYouEvents = collect();
        if ($user) {
            $followedUserIds = $user->following()->pluck('followed_id');
            if ($followedUserIds->isNotEmpty()) {
                $forYouEvents = Event::with(['category', 'user', 'city'])
                    ->whereIn('user_id', $followedUserIds)
                    ->where('status', 'approved')
                    ->where('event_date', '>=', now())
                    ->orderBy('event_date', 'asc')
                    ->take(7)
                    ->get();
            }
        }

        // En tu ciudad: eventos de la misma ciudad que el usuario
        $cityEvents = collect();
        if ($user && $user->city_id) {
            $cityEvents = Event::with(['category', 'user', 'city'])
                ->where('city_id', $user->city_id)
                ->where('status', 'approved')
                ->where('event_date', '>=', now())
                ->orderBy('event_date', 'asc')
                ->take(7)
                ->get();
        }

        // Según tus intereses: preparado para futura implementación
        // Por ahora, mostramos eventos aleatorios como placeholder
        $interestEvents = Event::with(['category', 'user', 'city'])
            ->where('status', 'approved')
            ->where('event_date', '>=', now())
            ->inRandomOrder()
            ->take(7)
            ->get();

        // Obtener nombre de la ciudad del usuario
        $userCityName = $user && $user->city ? $user->city->name : 'tu ciudad';

        return view('home', compact(
            'forYouEvents',
            'cityEvents', 
            'interestEvents',
            'userCityName'
        ));
    }
}
