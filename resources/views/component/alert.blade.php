@props(['type' => 'info', 'dismissible' => true])

@php
$classes = [
    'info' => 'bg-blue-500/10 border-blue-500/30 text-blue-500',
    'success' => 'bg-green-500/10 border-green-500/30 text-green-500',
    'warning' => 'bg-yellow-500/10 border-yellow-500/30 text-yellow-500',
    'error' => 'bg-red-500/10 border-red-500/30 text-red-500',
][$type] ?? 'bg-gray-500/10 border-gray-500/30 text-gray-500';

$icons = [
    'info' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
    'success' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
    'warning' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>',
    'error' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>',
][$type] ?? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
@endphp

<div {{ $attributes->merge(['class' => "p-4 border rounded-xl flex items-start space-x-3 $classes"]) }} 
    @if($dismissible) x-data="{ show: true }" x-show="show" x-transition @endif>
    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        {!! $icons !!}
    </svg>
    <div class="flex-1">
        {{ $slot }}
    </div>
    @if($dismissible)
    <button @click="show = false" class="flex-shrink-0 ml-2 hover:opacity-75 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
    @endif
</div>