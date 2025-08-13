<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $query = Game::active()->ordered();

        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('publisher', 'like', "%{$search}%");
            });
        }

        $games = $query->paginate(24);

        return Inertia::render('Games/Index', [
            'games' => $games,
            'filters' => $request->only(['category', 'search']),
        ]);
    }

    public function show($slug)
    {
        $game = Game::where('slug', $slug)
            ->active()
            ->with(['products' => function ($query) {
                $query->active()->ordered();
            }])
            ->firstOrFail();

        $testimonials = Testimonial::where('game_id', $game->id)
            ->approved()
            ->with('user')
            ->latest()
            ->paginate(10);

        $relatedGames = Game::active()
            ->where('id', '!=', $game->id)
            ->where('category', $game->category)
            ->ordered()
            ->limit(4)
            ->get();

        $game->increment('view_count');

        return Inertia::render('Games/Show', [
            'game' => $game,
            'testimonials' => $testimonials,
            'relatedGames' => $relatedGames,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $games = Game::active()
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('publisher', 'like', "%{$query}%");
            })
            ->ordered()
            ->paginate(24);

        return Inertia::render('Games/Search', [
            'games' => $games,
            'query' => $query,
        ]);
    }
}
