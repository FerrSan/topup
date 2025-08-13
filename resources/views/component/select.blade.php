@props(['disabled' => false, 'error' => false])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => 'w-full px-4 py-3 bg-zinc-800/50 border rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-accent-primary transition-colors ' . 
    ($error ? 'border-red-500 focus:border-red-500' : 'border-white/10 focus:border-transparent')
]) !!}>
    {{ $slot }}
</select>