<div>
    <!-- Status Badge -->
    <div class="mb-6">
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
        <span class="px-4 py-2 {{ $statusColors[$order->status] ?? 'bg-gray-500' }} text-white rounded-lg font-semibold">
            {{ str_replace('_', ' ', $order->status) }}
        </span>
    </div>

    <!-- Timeline -->
    <div class="space-y-4">
        @foreach($events as $event)
        <div class="flex items-start space-x-3" wire:key="event-{{ $event->id }}">
            <div class="w-2 h-2 bg-accent-primary rounded-full mt-2"></div>
            <div class="flex-1">
                <p class="text-white">{{ $event->description }}</p>
                <p class="text-gray-500 text-sm">{{ $event->created_at->format('d M Y, H:i:s') }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Refresh Button -->
    @if(in_array($order->status, ['PENDING', 'WAITING_PAYMENT', 'PAID', 'PROCESSING']))
    <button wire:click="checkPaymentStatus" wire:loading.attr="disabled"
        class="mt-6 px-4 py-2 bg-zinc-800 text-gray-300 rounded-xl hover:bg-zinc-700 transition disabled:opacity-50">
        <span wire:loading.remove wire:target="checkPaymentStatus">Check Payment Status</span>
        <span wire:loading wire:target="checkPaymentStatus">Checking...</span>
    </button>
    @endif

    <!-- Auto refresh indicator -->
    <div wire:poll.5s="refreshStatus" class="mt-4 text-gray-500 text-sm">
        <span class="inline-flex items-center">
            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse mr-2"></span>
            Live status updates enabled
        </span>
    </div>
</div>
