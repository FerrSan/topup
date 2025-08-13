@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full">
        <div class="bg-zinc-900/60 rounded-2xl p-8 ring-1 ring-white/10">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-white">Create Account</h1>
                <p class="text-gray-400 mt-2">Join us and start shopping</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <label class="block text-gray-300 text-sm mb-2">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required autofocus
                        class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary @error('name') border-red-500 @enderror"
                        placeholder="Enter your full name">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Username -->
                <div class="mb-4">
                    <label class="block text-gray-300 text-sm mb-2">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" required
                        class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary @error('username') border-red-500 @enderror"
                        placeholder="Choose a username">
                    @error('username')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-gray-300 text-sm mb-2">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary @error('email') border-red-500 @enderror"
                        placeholder="Enter your email">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div class="mb-4">
                    <label class="block text-gray-300 text-sm mb-2">Phone Number (Optional)</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary @error('phone') border-red-500 @enderror"
                        placeholder="+62 8xx-xxxx-xxxx">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="block text-gray-300 text-sm mb-2">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary @error('password') border-red-500 @enderror"
                        placeholder="Create a password">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label class="block text-gray-300 text-sm mb-2">Confirm Password</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary"
                        placeholder="Confirm your password">
                </div>

                <!-- Referral Code -->
                @if(request()->has('ref') || Cookie::has('referral_code'))
                <div class="mb-4">
                    <label class="block text-gray-300 text-sm mb-2">Referral Code</label>
                    <input type="text" name="referral_code" value="{{ request('ref') ?? Cookie::get('referral_code') }}" readonly
                        class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-gray-400 cursor-not-allowed">
                </div>
                @endif

                <!-- Terms -->
                <div class="mb-6">
                    <label class="flex items-start">
                        <input type="checkbox" name="terms" required class="mt-1 rounded bg-zinc-800 border-white/10 text-accent-primary focus:ring-accent-primary">
                        <span class="ml-2 text-gray-300 text-sm">
                            I agree to the 
                            <a href="#" class="text-accent-primary hover:text-accent-secondary">Terms of Service</a>
                            and 
                            <a href="#" class="text-accent-primary hover:text-accent-secondary">Privacy Policy</a>
                        </span>
                    </label>
                    @error('terms')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3 bg-accent-primary text-zinc-900 font-semibold rounded-xl hover:brightness-95 transition-all">
                    Create Account
                </button>
            </form>

            <!-- Sign In Link -->
            <p class="mt-8 text-center text-gray-400">
                Already have an account? 
                <a href="{{ route('login') }}" class="text-accent-primary hover:text-accent-secondary font-semibold">
                    Sign in
                </a>
            </p>
        </div>
    </div>
</div>
@endsection