<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameProduct;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function index($gameSlug)
    {
        $game = Game::where('slug', $gameSlug)->active()->firstOrFail();
        
        $products = GameProduct::where('game_id', $game->id)
            ->active()
            ->ordered()
            ->get();

        return response()->json([
            'game' => $game,
            'products' => $products,
        ]);
    }

    public function show($gameSlug, $productId)
    {
        $game = Game::where('slug', $gameSlug)->active()->firstOrFail();
        
        $product = GameProduct::where('game_id', $game->id)
            ->where('id', $productId)
            ->active()
            ->firstOrFail();

        return response()->json($product);
    }
}
