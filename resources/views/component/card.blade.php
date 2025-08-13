@props(['padding' => true])

<div {{ $attributes->merge(['class' => 'bg-zinc-900/60 rounded-2xl ring-1 ring-white/10' . ($padding ? ' p-6' : '')]) }}>
    {{ $slot }}
</div>

<!-- resources/views/components/button.blade.php -->
@props(['variant' => 'primary', 'size' => 'md', 'type' => 'button'])

@php
$baseClasses = 'inline-flex items-center justify-center font-semibold rounded-xl transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-zinc-900';

$variantClasses = [
    'primary' => 'bg-accent-primary text-zinc-900 hover:brightness-95 focus:ring-accent-primary',
    'secondary' => 'bg-zinc-800 text-gray-300 hover:bg-zinc-700 focus:ring-zinc-700',
    'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-600',
    'success' => 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-600',
    'ghost' => 'bg-transparent text-gray-300 hover:text-white hover:bg-zinc-800 focus:ring-zinc-700',
][$variant] ?? $variantClasses['primary'];

$sizeClasses = [
    'xs' => 'px-2.5 py-1.5 text-xs',
    'sm' => 'px-3 py-2 text-sm',
    'md' => 'px-4 py-2.5 text-sm',
    'lg' => 'px-5 py-3 text-base',
    'xl' => 'px-6 py-3.5 text-base',
][$size] ?? $sizeClasses['md'];
@endphp

<button 
    type="{{ $type }}"
    {{ $attributes->merge(['class' => "$baseClasses $variantClasses $sizeClasses"]) }}
>
    {{ $slot }}
</button>

<!-- resources/views/components/input.blade.php -->
@props(['disabled' => false, 'error' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => 'w-full px-4 py-3 bg-zinc-800/50 border rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary transition-colors ' . 
    ($error ? 'border-red-500 focus:border-red-500' : 'border-white/10 focus:border-transparent')
]) !!}>