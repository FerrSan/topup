<?php
// app/Http/Controllers/HomeController.php
namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Banner;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        $featuredGames = Game::active()
            ->featured()
            ->ordered()
            ->with('products')
            ->limit(8)
            ->get();

        $popularGames = Game::active()
            ->withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->limit(12)
            ->get();

        $banners = Banner::active()
            ->position('hero')
            ->ordered()
            ->get();

        $testimonials = Testimonial::approved()
            ->featured()
            ->highRated()
            ->with('user', 'game')
            ->latest()
            ->limit(6)
            ->get();

        return Inertia::render('Home/Index', [
            'featuredGames' => $featuredGames,
            'popularGames' => $popularGames,
            'banners' => $banners,
            'testimonials' => $testimonials,
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $games = Game::active()
            ->where('name', 'like', "%{$query}%")
            ->orWhere('publisher', 'like', "%{$query}%")
            ->ordered()
            ->paginate(24);

        return Inertia::render('Games/Search', [
            'games' => $games,
            'query' => $query,
        ]);
    }
}
