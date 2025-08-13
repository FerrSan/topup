@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-white mb-8">My Profile</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Info -->
        <div class="lg:col-span-1">
            <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10">
                <div class="text-center">
                    <img src="{{ $user->avatar_url }}" alt="Avatar" 
                        class="w-24 h-24 rounded-full mx-auto mb-4">
                    <h2 class="text-xl font-semibold text-white">{{ $user->name }}</h2>
                    <p class="text-gray-400">@{{ $user->username }}</p>
                    
                    <div class="mt-6 space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-400">Email</span>
                            <span class="text-white">{{ $user->email }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-400">Phone</span>
                            <span class="text-white">{{ $user->phone ?? '-' }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-400">Balance</span>
                            <span class="text-accent-primary font-semibold">{{ formatCurrency($user->balance) }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-400">Member Since</span>
                            <span class="text-white">{{ $user->created_at->format('d M Y') }}</span>
                        </div>
                    </div>

                    <a href="{{ route('profile.edit') }}" 
                        class="w-full mt-6 py-2 bg-zinc-800 text-gray-300 rounded-xl hover:bg-zinc-700 transition block">
                        Edit Profile
                    </a>
                </div>
            </div>

            <!-- Referral -->
            @if($user->referral_code)
            <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10 mt-6">
                <h3 class="text-lg font-semibold text-white mb-4">Referral Program</h3>
                <p class="text-gray-400 text-sm mb-4">Share your referral code and earn rewards!</p>
                
                <div class="bg-zinc-800/50 rounded-xl p-3 flex items-center justify-between">
                    <code class="text-accent-primary font-mono">{{ $user->referral_code }}</code>
                    <button onclick="copyToClipboard('{{ $user->referral_code }}')" 
                        class="text-gray-400 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </button>
                </div>
                
                <p class="text-gray-500 text-xs mt-2">
                    Referrals: {{ $user->referrals()->count() }}
                </p>
            </div>
            @endif
        </div>

        <!-- Statistics & Recent Orders -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Statistics -->
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10 text-center">
                    <p class="text-3xl font-bold text-white">{{ $statistics['total_orders'] }}</p>
                    <p class="text-gray-400 text-sm mt-1">Total Orders</p>
                </div>
                <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10 text-center">
                    <p class="text-3xl font-bold text-green-500">{{ $statistics['success_orders'] }}</p>
                    <p class="text-gray-400 text-sm mt-1">Success Orders</p>
                </div>
                <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10 text-center">
                    <p class="text-2xl font-bold text-accent-primary">{{ formatCurrency($statistics['total_spent']) }}</p>
                    <p class="text-gray-400 text-sm mt-1">Total Spent</p>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">Recent Orders</h3>
                    <a href="{{ route('profile.orders') }}" class="text-accent-primary hover:text-accent-secondary text-sm">
                        View All →
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($recentOrders as $order)
                    <a href="{{ route('invoice.show', $order->invoice_no) }}" 
                        class="block p-4 bg-zinc-800/50 rounded-xl hover:bg-zinc-800 transition">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $order->game->icon_url }}" alt="{{ $order->game->name }}" 
                                    class="w-10 h-10 rounded-lg">
                                <div>
                                    <p class="text-white font-medium">{{ $order->game->name }}</p>
                                    <p class="text-gray-400 text-sm">{{ $order->product->name }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-white font-semibold">{{ formatCurrency($order->grand_total) }}</p>
                                <p class="text-xs {{ $order->status === 'SUCCESS' ? 'text-green-500' : 'text-gray-500' }}">
                                    {{ $order->status }}
                                </p>
                            </div>
                        </div>
                    </a>
                    @empty
                    <p class="text-gray-400 text-center py-8">No orders yet</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            showToast('Referral code copied!', 'success');
        });
    }
</script>
@endpush
@endsection-primary focus:border-transparent"
                        placeholder="e.g. INV20240101ABC123"
                        value="{{ old('invoice_no') }}">
                    @error('invoice_no')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" 
                    class="w-full py-3 bg-accent-primary text-zinc-900 font-semibold rounded-xl hover:brightness-95 transition-all">
                    Check Invoice
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-400 text-sm">
                    Lost your invoice number? 
                    <a href="{{ route('login') }}" class="text-accent-primary hover:text-accent-secondary">
                        Login to view your orders
                    </a>
                </p>
            </div>
        </div>

        <!-- Recent Searches (if logged in) -->
        @auth
        @php
            $recentOrders = \App\Models\Order::where('user_id', auth()->id())
                ->latest()
                ->limit(5)
                ->get();
        @endphp
        @if($recentOrders->count() > 0)
        <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10 mt-6">
            <h3 class="text-white font-semibold mb-4">Your Recent Orders</h3>
            <div class="space-y-2">
                @foreach($recentOrders as $recentOrder)
                <a href="{{ route('invoice.show', $recentOrder->invoice_no) }}" 
                    class="block p-3 bg-zinc-800/50 rounded-lg hover:bg-zinc-800 transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white text-sm">{{ $recentOrder->invoice_no }}</p>
                            <p class="text-gray-500 text-xs">{{ $recentOrder->game->name }} - {{ $recentOrder->product->name }}</p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded {{ $recentOrder->status === 'SUCCESS' ? 'bg-green-500/20 text-green-500' : 'bg-gray-500/20 text-gray-400' }}">
                            {{ $recentOrder->status }}
                        </span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
        @endauth
    </div>
</div>
@endsection