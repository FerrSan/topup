@props(['id', 'maxWidth' => 'md'])

@php
$maxWidthClass = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth] ?? 'sm:max-w-md';
@endphp

<div x-data="{ open: false }" 
    @open-modal.window="if ($event.detail.id === '{{ $id }}') open = true"
    @close-modal.window="if ($event.detail.id === '{{ $id }}') open = false"
    x-show="open"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;">
    
    <div class="flex items-center justify-center min-h-screen px-4 text-center">
        <!-- Background overlay -->
        <div @click="open = false" class="fixed inset-0 bg-black/75 transition-opacity"></div>

        <!-- Modal panel -->
        <div x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative bg-zinc-900 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 w-full {{ $maxWidthClass }}">
            
            {{ $slot }}
        </div>
    </div>
</div>