<template>
    <input
        type="checkbox"
        :value="value"
        :checked="checked"
        @change="$emit('update:checked', $event.target.checked)"
        class="rounded bg-zinc-800 border-white/10 text-accent-primary focus:ring-accent-primary focus:ring-offset-zinc-900"
    >
</template>

<script setup>
defineProps({
    checked: {
        type: [Array, Boolean],
        default: false,
    },
    value: {
        default: null,
    },
});

defineEmits(['update:checked']);
</script>   