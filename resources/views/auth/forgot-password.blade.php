@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full">
        <div class="bg-zinc-900/60 rounded-2xl p-8 ring-1 ring-white/10">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white">Forgot Password?</h1>
                <p class="text-gray-400 mt-2">No worries, we'll send you reset instructions</p>
            </div>

            @if (session('status'))
            <div class="mb-4 p-4 bg-green-500/10 border border-green-500/30 rounded-xl">
                <p class="text-green-500 text-sm">{{ session('status') }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email -->
                <div class="mb-6">
                    <label class="block text-gray-300 text-sm mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary @error('email') border-red-500 @enderror"
                        placeholder="Enter your email">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 bg-accent-primary text-zinc-900 font-semibold rounded-xl hover:brightness-95 transition-all">
                    Send Reset Link
                </button>
            </form>

            <!-- Back to Login -->
            <p class="mt-8 text-center">
                <a href="{{ route('login') }}" class="text-accent-primary hover:text-accent-secondary font-semibold">
                    ← Back to Login
                </a>
            </p>
        </div>
    </div>
</div>
@endsection