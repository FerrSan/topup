<div wire:poll.30s="loadStats">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10">
            <p class="text-gray-400 text-sm">Today's Orders</p>
            <p class="text-2xl font-bold text-white mt-1">{{ number_format($todayOrders) }}</p>
        </div>
        
        <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10">
            <p class="text-gray-400 text-sm">Today's Revenue</p>
            <p class="text-2xl font-bold text-accent-primary mt-1">{{ formatCurrency($todayRevenue) }}</p>
        </div>
        
        <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10">
            <p class="text-gray-400 text-sm">Active Users</p>
            <p class="text-2xl font-bold text-white mt-1">{{ number_format($activeUsers) }}</p>
        </div>
        
        <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10">
            <p class="text-gray-400 text-sm">Success Rate</p>
            <p class="text-2xl font-bold text-green-500 mt-1">{{ $successRate }}%</p>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10">
        <h3 class="text-lg font-semibold text-white mb-4">Recent Orders</h3>
        
        <div class="space-y-3">
            @foreach($recentOrders as $order)
            <div class="flex items-center justify-between p-3 bg-zinc-800/50 rounded-xl">
                <div class="flex items-center space-x-3">
                    <img src="{{ $order->game->icon_url }}" alt="{{ $order->game->name }}" 
                        class="w-8 h-8 rounded-lg">
                    <div>
                        <p class="text-white text-sm">{{ $order->invoice_no }}</p>
                        <p class="text-gray-500 text-xs">{{ $order->game->name }} - {{ $order->product->name }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-white font-semibold text-sm">{{ formatCurrency($order->grand_total) }}</p>
                    <p class="text-xs {{ $order->status === 'SUCCESS' ? 'text-green-500' : 'text-gray-500' }}">
                        {{ $order->status }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>