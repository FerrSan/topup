<?php
// app/Http/Controllers/InvoiceController.php - UPDATE

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceController extends Controller
{
    public function check()
    {
        $recentOrders = [];
        
        if (auth()->check()) {
            $recentOrders = Order::where('user_id', auth()->id())
                ->with(['game', 'product'])
                ->latest()
                ->limit(5)
                ->get();
        }

        return Inertia::render('Invoice/Check', [
            'recentOrders' => $recentOrders
        ]);
    }

    public function show($invoiceNo)
    {
        $order = Order::with(['game', 'product', 'user', 'events'])
            ->where('invoice_no', $invoiceNo)
            ->firstOrFail();

        // Check if user can view this invoice
        if ($order->user_id && auth()->check() && $order->user_id !== auth()->id()) {
            abort(403, 'You are not authorized to view this invoice');
        }

        return Inertia::render('Invoice/Show', [
            'order' => $order,
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'invoice_no' => 'required|string',
        ]);

        $order = Order::where('invoice_no', $request->invoice_no)->first();

        if (!$order) {
            return back()->withErrors(['invoice_no' => 'Invoice not found']);
        }

        return redirect()->route('invoice.show', $order->invoice_no);
    }
}