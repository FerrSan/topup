<template>
    <button
        :type="type"
        class="px-6 py-3 bg-zinc-800 text-gray-300 font-semibold rounded-xl hover:bg-zinc-700 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-zinc-700 focus:ring-offset-zinc-900 disabled:opacity-50 disabled:cursor-not-allowed"
        :disabled="disabled"
    >
        <slot />
    </button>
</template>

<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
    disabled: Boolean,
});
</script>