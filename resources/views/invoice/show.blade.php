invoice/show.blade.php@extends('layouts.app')

@section('title', 'Invoice #' . $order->invoice_no)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="text-sm mb-6">
        <ol class="flex items-center space-x-2 text-gray-400">
            <li><a href="/" class="hover:text-white">Home</a></li>
            <li>/</li>
            <li class="text-white">Invoice #{{ $order->invoice_no }}</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Invoice Header -->
            <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-white mb-2">Invoice #{{ $order->invoice_no }}</h1>
                        <p class="text-gray-400">Created: {{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="text-right">
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
                                'CANCELLED' => 'bg-red-600',
                            ];
                        @endphp
                        <span class="px-4 py-2 {{ $statusColors[$order->status] ?? 'bg-gray-500' }} text-white rounded-lg font-semibold">
                            {{ str_replace('_', ' ', $order->status) }}
                        </span>
                    </div>
                </div>

                <!-- Status Timeline -->
                <div class="border-t border-white/10 pt-6">
                    <h3 class="text-lg font-semibold text-white mb-4">Status Timeline</h3>
                    <div class="space-y-4">
                        @foreach($order->events as $event)
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-accent-primary rounded-full mt-2"></div>
                            <div class="flex-1">
                                <p class="text-white">{{ $event->description }}</p>
                                <p class="text-gray-500 text-sm">{{ $event->created_at->format('d M Y, H:i:s') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Realtime Status Update -->
                @if(in_array($order->status, ['PENDING', 'WAITING_PAYMENT', 'PAID', 'PROCESSING']))
                <div class="border-t border-white/10 pt-6">
                    <div class="flex items-center space-x-2 text-yellow-500">
                        <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Your order is being processed...</span>
                    </div>
                </div>
                @endif
            </div>

            <!-- Order Details -->
            <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10">
                <h3 class="text-lg font-semibold text-white mb-4">Order Details</h3>
                
                <div class="space-y-4">
                    <!-- Game Info -->
                    <div class="flex items-start space-x-4">
                        <img src="{{ $order->game->icon_url }}" alt="{{ $order->game->name }}" 
                            class="w-16 h-16 rounded-xl">
                        <div class="flex-1">
                            <h4 class="text-white font-medium">{{ $order->game->name }}</h4>
                            <p class="text-gray-400">{{ $order->product->name }}</p>
                            <p class="text-gray-500 text-sm">Quantity: {{ $order->qty }}</p>
                        </div>
                    </div>

                    <!-- Player Info -->
                    <div class="border-t border-white/10 pt-4">
                        <h4 class="text-white font-medium mb-2">Player Information</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-400">User ID</p>
                                <p class="text-white">{{ $order->player_uid }}</p>
                            </div>
                            @if($order->player_server)
                            <div>
                                <p class="text-gray-400">Server ID</p>
                                <p class="text-white">{{ $order->player_server }}</p>
                            </div>
                            @endif
                            @if($order->player_name)
                            <div>
                                <p class="text-gray-400">Player Name</p>
                                <p class="text-white">{{ $order->player_name }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Info -->
                    <div class="border-t border-white/10 pt-4">
                        <h4 class="text-white font-medium mb-2">Payment Information</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-400">Payment Method</p>
                                <p class="text-white">{{ strtoupper(str_replace('_', ' ', $order->payment_method ?? '-')) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400">Payment Provider</p>
                                <p class="text-white">{{ ucfirst($order->payment_provider ?? '-') }}</p>
                            </div>
                            @if($order->paid_at)
                            <div>
                                <p class="text-gray-400">Paid At</p>
                                <p class="text-white">{{ $order->paid_at->format('d M Y, H:i') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($order->buyer_note)
                    <div class="border-t border-white/10 pt-4">
                        <h4 class="text-white font-medium mb-2">Notes</h4>
                        <p class="text-gray-300 text-sm">{{ $order->buyer_note }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            @if($order->status === 'SUCCESS' && !$order->testimonial)
            <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10">
                <h3 class="text-lg font-semibold text-white mb-4">Rate Your Experience</h3>
                <p class="text-gray-400 mb-4">How was your experience with this purchase?</p>
                <a href="{{ route('testimonial.create', $order->id) }}" 
                    class="inline-flex items-center px-6 py-3 bg-accent-primary text-zinc-900 font-semibold rounded-xl hover:brightness-95 transition">
                    Write a Review
                </a>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Price Summary -->
            <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10 sticky top-24">
                <h3 class="text-lg font-semibold text-white mb-4">Price Summary</h3>
                
                <div class="space-y-3 mb-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Subtotal</span>
                        <span class="text-white">{{ formatCurrency($order->price) }}</span>
                    </div>
                    @if($order->discount > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Discount</span>
                        <span class="text-green-500">- {{ formatCurrency($order->discount) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Fee</span>
                        <span class="text-white">{{ formatCurrency($order->fee) }}</span>
                    </div>
                </div>
                
                <div class="border-t border-white/10 pt-4">
                    <div class="flex justify-between">
                        <span class="text-white font-semibold">Total</span>
                        <span class="text-accent-primary text-xl font-bold">{{ formatCurrency($order->grand_total) }}</span>
                    </div>
                </div>

                <!-- Payment Button -->
                @if($order->status === 'WAITING_PAYMENT' && $order->payment_url)
                <a href="{{ $order->payment_url }}" target="_blank"
                    class="w-full mt-6 py-3 bg-accent-primary text-zinc-900 font-semibold rounded-xl hover:brightness-95 transition-all text-center block">
                    Continue Payment
                </a>
                @endif

                <!-- Download Invoice -->
                @if(in_array($order->status, ['SUCCESS', 'REFUNDED']))
                <a href="{{ route('invoice.download', $order->invoice_no) }}" 
                    class="w-full mt-4 py-3 bg-zinc-800 text-gray-300 font-semibold rounded-xl hover:bg-zinc-700 transition-all text-center block">
                    Download Invoice
                </a>
                @endif

                <!-- Customer Support -->
                <div class="mt-6 p-4 bg-zinc-800/50 rounded-xl">
                    <p class="text-gray-400 text-sm mb-2">Need help?</p>
                    <a href="https://wa.me/{{ config('services.whatsapp.number') }}?text=Hi, I need help with invoice {{ $order->invoice_no }}" 
                        target="_blank"
                        class="flex items-center space-x-2 text-green-500 hover:text-green-400 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                        <span>Contact Support</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Listen for realtime updates
    @if(in_array($order->status, ['PENDING', 'WAITING_PAYMENT', 'PAID', 'PROCESSING']))
    Echo.private('orders.{{ $order->invoice_no }}')
        .listen('.order.paid', (e) => {
            showToast('Payment received! Processing your order...', 'success');
            setTimeout(() => location.reload(), 2000);
        })
        .listen('.order.processing', (e) => {
            showToast('Processing your top-up...', 'info');
        })
        .listen('.order.completed', (e) => {
            showToast('Top-up completed successfully!', 'success');
            setTimeout(() => location.reload(), 2000);
        })
        .listen('.order.failed', (e) => {
            showToast('Top-up failed. Refund will be processed.', 'error');
            setTimeout(() => location.reload(), 2000);
        });
    @endif
</script>
@endpush
@endsection
