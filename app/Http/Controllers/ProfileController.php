<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        
        $recentOrders = Order::where('user_id', $user->id)
            ->with(['game', 'product'])
            ->latest()
            ->limit(10)
            ->get();

        $statistics = [
            'total_orders' => Order::where('user_id', $user->id)->count(),
            'success_orders' => Order::where('user_id', $user->id)->success()->count(),
            'total_spent' => Order::where('user_id', $user->id)->success()->sum('grand_total'),
        ];

        return Inertia::render('Profile/Index', [
            'user' => $user,
            'recentOrders' => $recentOrders,
            'statistics' => $statistics,
        ]);
    }

    public function edit()
    {
        return Inertia::render('Profile/Edit', [
            'user' => auth()->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'avatar_url' => 'nullable|url',
        ]);

        $user->update($validated);

        return redirect()->route('profile.index')
            ->with('success', 'Profile updated successfully');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully');
    }

    public function orders(Request $request)
    {
        $query = Order::where('user_id', auth()->id())
            ->with(['game', 'product']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('game_id')) {
            $query->where('game_id', $request->game_id);
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        $orders = $query->latest()->paginate(20);

        return Inertia::render('Profile/Orders', [
            'orders' => $orders,
            'filters' => $request->only(['status', 'game_id', 'date_from']),
        ]);
    }

    public function testimonials()
    {
        $testimonials = Testimonial::where('user_id', auth()->id())
            ->with(['game', 'order'])
            ->latest()
            ->paginate(20);

        return Inertia::render('Profile/Testimonials', [
            'testimonials' => $testimonials,
        ]);
    }
}