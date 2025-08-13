<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;

class GameApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Game::active()->with('products');

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        if ($request->has('featured')) {
            $query->where('is_featured', true);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('publisher', 'like', "%{$search}%");
            });
        }

        $games = $query->ordered()->paginate($request->per_page ?? 20);

        return response()->json($games);
    }

    public function show($slug)
    {
        $game = Game::where('slug', $slug)
            ->active()
            ->with(['products' => function ($query) {
                $query->active()->ordered();
            }])
            ->firstOrFail();

        return response()->json($game);
    }
}