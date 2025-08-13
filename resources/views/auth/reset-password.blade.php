@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full">
        <div class="bg-zinc-900/60 rounded-2xl p-8 ring-1 ring-white/10">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white">Reset Password</h1>
                <p class="text-gray-400 mt-2">Enter your new password below</p>
            </div>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-gray-300 text-sm mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus
                        class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary @error('email') border-red-500 @enderror"
                        placeholder="Enter your email">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div class="mb-4">
                    <label class="block text-gray-300 text-sm mb-2">New Password</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary @error('password') border-red-500 @enderror"
                        placeholder="Enter new password">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label class="block text-gray-300 text-sm mb-2">Confirm New Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary"
                        placeholder="Confirm new password">
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 bg-accent-primary text-zinc-900 font-semibold rounded-xl hover:brightness-95 transition-all">
                    Reset Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection