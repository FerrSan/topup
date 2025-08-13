<template>
    <button
        :type="type"
        class="px-6 py-3 bg-accent-primary text-zinc-900 font-semibold rounded-xl hover:brightness-95 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-primary focus:ring-offset-zinc-900 disabled:opacity-50 disabled:cursor-not-allowed"
        :disabled="disabled"
    >
        <slot />
    </button>
</template>

<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
    disabled: Boolean,
});
</script>