@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <h1 class="text-3xl font-bold text-white mb-8">Edit Profile</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10">
                <div class="text-center">
                    <img src="{{ $user->avatar_url }}" alt="Avatar" 
                        class="w-24 h-24 rounded-full mx-auto mb-4">
                    <h2 class="text-xl font-semibold text-white">{{ $user->name }}</h2>
                    <p class="text-gray-400">@{{ $user->username }}</p>
                </div>

                <nav class="mt-6 space-y-2">
                    <a href="{{ route('profile.index') }}" 
                        class="block px-4 py-2 text-gray-300 hover:text-white hover:bg-zinc-800 rounded-xl transition">
                        Profile Overview
                    </a>
                    <a href="{{ route('profile.edit') }}" 
                        class="block px-4 py-2 text-white bg-accent-primary/20 rounded-xl">
                        Edit Profile
                    </a>
                    <a href="{{ route('profile.orders') }}" 
                        class="block px-4 py-2 text-gray-300 hover:text-white hover:bg-zinc-800 rounded-xl transition">
                        Order History
                    </a>
                    <a href="{{ route('profile.testimonials') }}" 
                        class="block px-4 py-2 text-gray-300 hover:text-white hover:bg-zinc-800 rounded-xl transition">
                        My Reviews
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10">
                <h3 class="text-lg font-semibold text-white mb-4">Basic Information</h3>
                
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-300 text-sm mb-2">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-gray-300 text-sm mb-2">Username</label>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}" required
                                class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary @error('username') border-red-500 @enderror">
                            @error('username')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-gray-300 text-sm mb-2">Email</label>
                            <input type="email" value="{{ $user->email }}" disabled
                                class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-gray-400 cursor-not-allowed">
                            <p class="text-gray-500 text-xs mt-1">Email cannot be changed</p>
                        </div>
                        
                        <div>
                            <label class="block text-gray-300 text-sm mb-2">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary @error('phone') border-red-500 @enderror"
                                placeholder="+62 8xx-xxxx-xxxx">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="block text-gray-300 text-sm mb-2">Avatar URL</label>
                        <input type="url" name="avatar_url" value="{{ old('avatar_url', $user->avatar_url) }}"
                            class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary"
                            placeholder="https://example.com/avatar.jpg">
                    </div>
                    
                    <button type="submit" 
                        class="mt-6 px-6 py-3 bg-accent-primary text-zinc-900 font-semibold rounded-xl hover:brightness-95 transition">
                        Save Changes
                    </button>
                </form>
            </div>

            <!-- Change Password -->
            <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10">
                <h3 class="text-lg font-semibold text-white mb-4">Change Password</h3>
                
                <form action="{{ route('profile.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-gray-300 text-sm mb-2">Current Password</label>
                            <input type="password" name="current_password" required
                                class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary @error('current_password') border-red-500 @enderror">
                            @error('current_password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-gray-300 text-sm mb-2">New Password</label>
                            <input type="password" name="password" required
                                class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-gray-300 text-sm mb-2">Confirm New Password</label>
                            <input type="password" name="password_confirmation" required
                                class="w-full px-4 py-3 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary">
                        </div>
                    </div>
                    
                    <button type="submit" 
                        class="mt-6 px-6 py-3 bg-zinc-800 text-gray-300 font-semibold rounded-xl hover:bg-zinc-700 transition">
                        Update Password
                    </button>
                </form>
            </div>

            <!-- Two-Factor Authentication -->
            <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10">
                <h3 class="text-lg font-semibold text-white mb-4">Two-Factor Authentication</h3>
                
                @if(!$user->two_factor_secret)
                <p class="text-gray-400 mb-4">Add an extra layer of security to your account.</p>
                <button class="px-6 py-3 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition">
                    Enable 2FA
                </button>
                @else
                <p class="text-green-500 mb-4">✓ Two-factor authentication is enabled</p>
                <button class="px-6 py-3 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition">
                    Disable 2FA
                </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection