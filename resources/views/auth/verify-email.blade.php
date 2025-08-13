@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full">
        <div class="bg-zinc-900/60 rounded-2xl p-8 ring-1 ring-white/10 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-accent-primary/20 rounded-full mb-6">
                <svg class="w-8 h-8 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-white mb-2">Verify Your Email</h1>
            <p class="text-gray-400 mb-6">
                Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?
            </p>

            @if (session('status') == 'verification-link-sent')
            <div class="mb-4 p-4 bg-green-500/10 border border-green-500/30 rounded-xl">
                <p class="text-green-500 text-sm">
                    A new verification link has been sent to your email address.
                </p>
            </div>
            @endif

            <div class="space-y-3">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="w-full py-3 bg-accent-primary text-zinc-900 font-semibold rounded-xl hover:brightness-95 transition-all">
                        Resend Verification Email
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full py-3 bg-zinc-800 text-gray-300 font-semibold rounded-xl hover:bg-zinc-700 transition-all">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection