@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="text-center">
        <h1 class="text-9xl font-bold text-accent-primary">404</h1>
        <h2 class="text-3xl font-bold text-white mt-4">Page Not Found</h2>
        <p class="text-gray-400 mt-4 mb-8">The page you're looking for doesn't exist or has been moved.</p>
        <a href="/" class="inline-block px-8 py-3 bg-accent-primary text-zinc-900 font-semibold rounded-xl hover:brightness-95 transition">
            Go Home
        </a>
    </div>
</div>
@endsection