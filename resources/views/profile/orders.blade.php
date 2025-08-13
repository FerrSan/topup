@extends('layouts.app')

@section('title', 'Order History')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-white mb-8">Order History</h1>

    <!-- Filters -->
    <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10 mb-6">
        <form method="GET" action="{{ route('profile.orders') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-gray-300 text-sm mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 bg-zinc-800/50 border border-white/10 rounded-xl text-white">
                    <option value="">All Status</option>
                    <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }}>Pending</option>
                    <option value="PAID" {{ request('status') == 'PAID' ? 'selected' : '' }}>Paid</option>
                    <option value="SUCCESS" {{ request('status') == 'SUCCESS' ? 'selected' : '' }}>Success</option>
                    <option value="FAILED" {{ request('status') == 'FAILED' ? 'selected' : '' }}>Failed</option>
                    <option value="REFUNDED" {{ request('status') == 'REFUNDED' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>
            
            <div>
                <label class="block text-gray-300 text-sm mb-2">Game</label>
                <select name="game_id" class="w-full px-4 py-2 bg-zinc-800/50 border border-white/10 rounded-xl text-white">
                    <option value="">All Games</option>
                    @foreach(\App\Models\Game::active()->get() as $game)
                    <option value="{{ $game->id }}" {{ request('game_id') == $game->id ? 'selected' : '' }}>
                        {{ $game->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-gray-300 text-sm mb-2">Date Range</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                    class="w-full px-4 py-2 bg-zinc-800/50 border border-white/10 rounded-xl text-white">
            </div>
            
            <div>
                <label class="block text-gray-300 text-sm mb-2">&nbsp;</label>
                <button type="submit" class="w-full px-4 py-2 bg-accent-primary text-zinc-900 font-semibold rounded-xl hover:brightness-95 transition">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Orders List -->
    <div class="space-y-4">
        @forelse($orders as $order)
        <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10">
            <div class="flex items-start justify-between">
                <div class="flex items-start space-x-4">
                    <img src="{{ $order->game->icon_url }}" alt="{{ $order->game->name }}" 
                        class="w-16 h-16 rounded-xl">
                    <div>
                        <h3 class="text-white font-semibold">{{ $order->game->name }}</h3>
                        <p class="text-gray-400">{{ $order->product->name }} × {{ $order->qty }}</p>
                        <p class="text-gray-500 text-sm mt-1">
                            Invoice: <span class="text-gray-300">{{ $order->invoice_no }}</span>
                        </p>
                        <p class="text-gray-500 text-sm">
                            {{ $order->created_at->format('d M Y, H:i') }}
                        </p>
                    </div>
                </div>
                
                <div class="text-right">
                    <p class="text-white font-semibold text-lg">{{ formatCurrency($order->grand_total) }}</p>
                    @php
                        $statusColors = [
                            'PENDING' => 'bg-gray-500',
                            'WAITING_PAYMENT' => 'bg-yellow-500',
                            'PAID' => 'bg-blue-500',
                            'PROCESSING' => 'bg-indigo-500',
                            'SUCCESS' => 'bg-green-500',
                            'FAILED' => 'bg-red-500',
                            'REFUNDED' => 'bg-purple-500',
                            'EXPIRED' => 'bg-gray-600',
                        ];
                    @endphp
                    <span class="inline-block mt-2 px-3 py-1 {{ $statusColors[$order->status] ?? 'bg-gray-500' }} text-white text-sm rounded-lg">
                        {{ str_replace('_', ' ', $order->status) }}
                    </span>
                    
                    <div class="mt-4 space-x-2">
                        <a href="{{ route('invoice.show', $order->invoice_no) }}" 
                            class="inline-block px-4 py-2 bg-zinc-800 text-gray-300 text-sm rounded-lg hover:bg-zinc-700 transition">
                            View Invoice
                        </a>
                        
                        @if($order->status === 'SUCCESS' && !$order->testimonial)
                        <a href="{{ route('testimonial.create', $order->id) }}" 
                            class="inline-block px-4 py-2 bg-accent-primary text-zinc-900 text-sm font-semibold rounded-lg hover:brightness-95 transition">
                            Write Review
                        </a>
                        @endif
                        
                        @if($order->status === 'WAITING_PAYMENT' && $order->payment_url)
                        <a href="{{ $order->payment_url }}" target="_blank"
                            class="inline-block px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
                            Pay Now
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-zinc-900/60 rounded-2xl p-12 ring-1 ring-white/10 text-center">
            <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <p class="text-gray-400 text-lg">No orders found</p>
            <a href="{{ route('games.index') }}" class="inline-block mt-4 px-6 py-3 bg-accent-primary text-zinc-900 font-semibold rounded-xl hover:brightness-95 transition">
                Start Shopping
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $orders->links() }}
    </div>
</div>
@endsection