@extends('layouts.app')

@section('title', 'Service Unavailable')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="text-center">
        <h1 class="text-9xl font-bold text-yellow-500">503</h1>
        <h2 class="text-3xl font-bold text-white mt-4">Service Unavailable</h2>
        <p class="text-gray-400 mt-4 mb-8">We're currently performing maintenance. Please check back soon.</p>
        <div class="inline-flex items-center space-x-2 text-gray-500">
            <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Estimated time: 30 minutes</span>
        </div>
    </div>
</div>
@endsection