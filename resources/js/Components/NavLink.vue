<template>
    <Link
        :href="href"
        :class="classes"
    >
        <slot />
    </Link>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() =>
    props.active
        ? 'block px-4 py-2 text-white bg-accent-primary/20 rounded-xl'
        : 'block px-4 py-2 text-gray-300 hover:text-white hover:bg-zinc-800 rounded-xl transition'
);
</script>