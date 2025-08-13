@props(['value', 'required' => false])

<label {{ $attributes->merge(['class' => 'block text-gray-300 text-sm mb-2']) }}>
    {{ $value ?? $slot }}
    @if($required)
        <span class="text-red-500 ml-1">*</span>
    @endif
</label>