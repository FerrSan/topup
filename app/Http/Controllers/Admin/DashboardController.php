<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Game;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $todayOrders = Order::whereDate('created_at', today())->count();
        $todayRevenue = Order::whereDate('created_at', today())
            ->whereIn('status', ['SUCCESS', 'PAID', 'PROCESSING'])
            ->sum('grand_total');
        $totalUsers = User::count();
        $totalGames = Game::active()->count();

        $recentOrders = Order::with(['game', 'product', 'user'])
            ->latest()
            ->limit(10)
            ->get();

        $popularGames = Game::withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->limit(5)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'todayOrders' => $todayOrders,
                'todayRevenue' => $todayRevenue,
                'totalUsers' => $totalUsers,
                'totalGames' => $totalGames,
            ],
            'recentOrders' => $recentOrders,
            'popularGames' => $popularGames,
        ]);
    }
}
