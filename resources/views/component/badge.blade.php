@props(['color' => 'gray', 'size' => 'md'])

@php
$colorClasses = [
    'gray' => 'bg-gray-500/20 text-gray-400',
    'red' => 'bg-red-500/20 text-red-500',
    'yellow' => 'bg-yellow-500/20 text-yellow-500',
    'green' => 'bg-green-500/20 text-green-500',
    'blue' => 'bg-blue-500/20 text-blue-500',
    'indigo' => 'bg-indigo-500/20 text-indigo-500',
    'purple' => 'bg-purple-500/20 text-purple-500',
    'pink' => 'bg-pink-500/20 text-pink-500',
][$color] ?? 'bg-gray-500/20 text-gray-400';

$sizeClasses = [
    'sm' => 'px-2 py-0.5 text-xs',
    'md' => 'px-2.5 py-1 text-sm',
    'lg' => 'px-3 py-1.5 text-base',
][$size] ?? 'px-2.5 py-1 text-sm';
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center font-medium rounded-lg $colorClasses $sizeClasses"]) }}>
    {{ $slot }}
</span>