<template>
    <button
        :type="type"
        class="px-6 py-3 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-600 focus:ring-offset-zinc-900 disabled:opacity-50 disabled:cursor-not-allowed"
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