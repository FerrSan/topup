@extends('layouts.app')

@section('title', 'Check Invoice')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="max-w-md w-full">
        <div class="bg-zinc-900/60 rounded-2xl p-8 ring-1 ring-white/10">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">Check Invoice</h1>
                <p class="text-gray-400">Enter your invoice number to track your order</p>
            </div>

            <form action="{{ route('invoice.search') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-gray-300 text-sm mb-2">Invoice Number</label>
                    <input type="text" name="invoice_no" required
                        class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary"
                    placeholder="Enter your name" value="{{ old('name') }}">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endguest

            <div class="flex space-x-4">
                <button type="submit" 
                    class="flex-1 py-3 bg-accent-primary text-zinc-900 font-semibold rounded-xl hover:brightness-95 transition-all">
                    Submit Review
                </button>
                <a href="{{ route('invoice.show', $order->invoice_no) }}" 
                    class="flex-1 py-3 bg-zinc-800 text-gray-300 font-semibold rounded-xl hover:bg-zinc-700 transition-all text-center">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection