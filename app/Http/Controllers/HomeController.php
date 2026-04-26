<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use App\Models\Tag;

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
        // Obtener eventos por categorías para los carruseles
        $categories = Category::with('events')->get();
        $tags = Tag::all();
        $eventsByCategory = [];

        foreach ($categories as $category) {
            $eventsByCategory[$category->name] = $category->events()
                ->where('status', 'approved')
                ->where('event_date', '>=', now())
                ->orderBy('event_date', 'asc')
                ->take(10)
                ->get();
        }

        // Eventos destacados (más populares o recientes)
        $featuredEvents = Event::with(['category', 'user'])
            ->where('status', 'approved')
            ->where('event_date', '>=', now())
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        // Eventos del usuario autenticado (creados por él)
        $myEvents = auth()->user() ? auth()->user()->events()
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->take(5)
            ->get() : collect();

        // Eventos recomendados (basado en categorías de interés del usuario)
        $recommendedEvents = Event::with(['category', 'user'])
            ->where('status', 'approved')
            ->where('event_date', '>=', now())
            ->inRandomOrder()
            ->take(6)
            ->get();

        return view('home', compact(
            'eventsByCategory',
            'featuredEvents',
            'myEvents',
            'recommendedEvents',
            'categories',
            'tags'
        ));
    }
}
