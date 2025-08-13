@props(['disabled' => false, 'error' => false])

<textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => 'w-full px-4 py-3 bg-zinc-800/50 border rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary transition-colors resize-none ' . 
    ($error ? 'border-red-500 focus:border-red-500' : 'border-white/10 focus:border-transparent')
]) !!}>{{ $slot }}</textarea>