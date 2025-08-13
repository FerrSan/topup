@extends('layouts.app')

@section('title', 'My Reviews')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-white mb-8">My Reviews</h1>

    <div class="space-y-4">
        @forelse($testimonials as $testimonial)
        <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10">
            <div class="flex items-start justify-between">
                <div class="flex items-start space-x-4">
                    <img src="{{ $testimonial->game->icon_url }}" alt="{{ $testimonial->game->name }}" 
                        class="w-12 h-12 rounded-xl">
                    <div class="flex-1">
                        <h3 class="text-white font-semibold">{{ $testimonial->game->name }}</h3>
                        <div class="flex items-center mt-1">
                            @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-600' }}" 
                                fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            @endfor
                            <span class="text-gray-500 text-sm ml-2">{{ $testimonial->created_at->format('d M Y') }}</span>
                        </div>
                        <p class="text-gray-300 mt-3">{{ $testimonial->comment }}</p>
                        
                        @if($testimonial->order)
                        <p class="text-gray-500 text-sm mt-2">
                            Order: <a href="{{ route('invoice.show', $testimonial->order->invoice_no) }}" 
                                class="text-accent-primary hover:text-accent-secondary">
                                #{{ $testimonial->order->invoice_no }}
                            </a>
                        </p>
                        @endif
                    </div>
                </div>
                
                <div class="text-right">
                    @if($testimonial->is_approved)
                    <span class="inline-block px-3 py-1 bg-green-500/20 text-green-500 text-sm rounded-lg">
                        Approved
                    </span>
                    @else
                    <span class="inline-block px-3 py-1 bg-yellow-500/20 text-yellow-500 text-sm rounded-lg">
                        Pending Review
                    </span>
                    @endif
                    
                    @if($testimonial->is_featured)
                    <span class="inline-block mt-2 px-3 py-1 bg-purple-500/20 text-purple-500 text-sm rounded-lg">
                        Featured
                    </span>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-zinc-900/60 rounded-2xl p-12 ring-1 ring-white/10 text-center">
            <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.396-1.21 1.502-1.21 1.898 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
            </svg>
            <p class="text-gray-400 text-lg">You haven't written any reviews yet</p>
            <a href="{{ route('profile.orders') }}" class="inline-block mt-4 px-6 py-3 bg-accent-primary text-zinc-900 font-semibold rounded-xl hover:brightness-95 transition">
                View Your Orders
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $testimonials->links() }}
    </div>
</div>
@endsection