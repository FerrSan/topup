testimonial/create.blade.php@extends('layouts.app')

@section('title', 'Write a Review')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="bg-zinc-900/60 rounded-2xl p-8 ring-1 ring-white/10">
        <h1 class="text-2xl font-bold text-white mb-6">Write a Review</h1>
        
        <!-- Order Info -->
        <div class="bg-zinc-800/50 rounded-xl p-4 mb-6">
            <div class="flex items-center space-x-4">
                <img src="{{ $order->game->icon_url }}" alt="{{ $order->game->name }}" 
                    class="w-12 h-12 rounded-lg">
                <div>
                    <h3 class="text-white font-medium">{{ $order->game->name }}</h3>
                    <p class="text-gray-400 text-sm">{{ $order->product->name }} - Invoice #{{ $order->invoice_no }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('testimonial.store', $order->id) }}" method="POST">
            @csrf
            
            <!-- Rating -->
            <div class="mb-6">
                <label class="block text-gray-300 text-sm mb-2">Rating</label>
                <div class="flex space-x-2" x-data="{ rating: 5 }">
                    @for($i = 1; $i <= 5; $i++)
                    <button type="button" @click="rating = {{ $i }}"
                        class="focus:outline-none">
                        <svg class="w-8 h-8 transition-colors"
                            :class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-600'"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </button>
                    <input type="hidden" name="rating" x-model="rating">
                    @endfor
                </div>
                @error('rating')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Comment -->
            <div class="mb-6">
                <label class="block text-gray-300 text-sm mb-2">Your Review</label>
                <textarea name="comment" rows="5" required
                    class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary"
                    placeholder="Share your experience with this purchase...">{{ old('comment') }}</textarea>
                @error('comment')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Name (for guests) -->
            @guest
            <div class="mb-6">
                <label class="block text-gray-300 text-sm mb-2">Your Name</label>
                <input type="text" name="name" required
                    class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent