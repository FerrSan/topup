@extends('layouts.app')

@section('title', 'Access Forbidden')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="text-center">
        <h1 class="text-9xl font-bold text-red-500">403</h1>
        <h2 class="text-3xl font-bold text-white mt-4">Access Forbidden</h2>
        <p class="text-gray-400 mt-4 mb-8">You don't have permission to access this resource.</p>
        <a href="/" class="inline-block px-8 py-3 bg-accent-primary text-zinc-900 font-semibold rounded-xl hover:brightness-95 transition">
            Go Home
        </a>
    </div>
</div>
@endsection