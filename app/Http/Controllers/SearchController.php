<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use App\Models\Tag;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        // Get all categories and tags for filters
        $categories = Category::all();
        $tags = Tag::all();

        // Build query
        $query = Event::with(['category', 'user', 'city'])
            ->where('status', 'approved')
            ->where('event_date', '>=', now());

        // Search by query term (from navbar search)
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('city', function($cityQuery) use ($searchTerm) {
                      $cityQuery->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // Apply filters
        if ($request->filled('date')) {
            $query->whereDate('event_date', $request->date);
        }

        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%')
                  ->orWhereHas('city', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->location . '%');
                  });
        }

        if ($request->has('category') && !empty($request->category)) {
            $query->whereIn('category_id', $request->category);
        }

        if ($request->has('tags') && !empty($request->tags)) {
            $query->whereHas('tags', function($q) use ($request) {
                $q->whereIn('tags.id', $request->tags);
            });
        }

        // Get results
        $events = $query->orderBy('event_date', 'asc')->paginate(12);

        return view('search', compact('events', 'categories', 'tags'));
    }
}
