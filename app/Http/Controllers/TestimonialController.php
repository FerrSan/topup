<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function create($orderId)
    {
        $order = Order::where('id', $orderId)
            ->success()
            ->firstOrFail();

        // Check if user can create testimonial for this order
        if ($order->user_id && auth()->check() && $order->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if testimonial already exists
        if ($order->testimonial) {
            return redirect()->route('invoice.show', $order->invoice_no)
                ->with('info', 'You have already submitted a testimonial for this order');
        }

        return view('testimonial.create', compact('order'));
    }

    public function store(Request $request, $orderId)
    {
        $order = Order::where('id', $orderId)
            ->success()
            ->firstOrFail();

        // Authorization check
        if ($order->user_id && auth()->check() && $order->user_id !== auth()->id()) {
            abort(403);
        }

        // Check if testimonial already exists
        if ($order->testimonial) {
            return redirect()->route('invoice.show', $order->invoice_no)
                ->with('error', 'Testimonial already exists');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:500',
            'name' => 'required_without:user_id|string|max:100',
        ]);

        $testimonial = Testimonial::create([
            'user_id' => auth()->id(),
            'order_id' => $order->id,
            'game_id' => $order->game_id,
            'name' => auth()->check() ? null : $validated['name'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('invoice.show', $order->invoice_no)
            ->with('success', 'Thank you for your testimonial! It will be reviewed shortly.');
    }
}