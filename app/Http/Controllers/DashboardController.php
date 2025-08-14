<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        
        // Basic stats for user dashboard
        $stats = [
            'total_orders' => Order::where('user_id', $user->id)->count(),
            'success_orders' => Order::where('user_id', $user->id)->where('status', 'SUCCESS')->count(),
            'pending_orders' => Order::where('user_id', $user->id)->whereIn('status', ['PENDING', 'WAITING_PAYMENT', 'PROCESSING'])->count(),
            'total_spent' => Order::where('user_id', $user->id)->where('status', 'SUCCESS')->sum('grand_total'),
        ];

        // Recent orders
        $recentOrders = Order::where('user_id', $user->id)
            ->with(['game', 'product'])
            ->latest()
            ->limit(5)
            ->get();

        // Popular games
        $popularGames = Game::active()
            ->withCount(['orders' => function($query) {
                $query->where('status', 'SUCCESS');
            }])
            ->orderBy('orders_count', 'desc')
            ->limit(6)
            ->get();

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'recentOrders' => $recentOrders,
            'popularGames' => $popularGames,
        ]);
    }
}