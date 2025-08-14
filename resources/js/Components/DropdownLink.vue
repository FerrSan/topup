<template>
    <div>
        <button
            v-if="as === 'button'"
            type="submit"
            class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-300 hover:bg-zinc-800 hover:text-white focus:outline-none focus:bg-zinc-800 transition duration-150 ease-in-out"
        >
            <slot />
        </button>

        <Link
            v-else
            :href="href"
            :method="method"
            :as="as"
            class="block px-4 py-2 text-sm leading-5 text-gray-300 hover:bg-zinc-800 hover:text-white focus:outline-none focus:bg-zinc-800 transition duration-150 ease-in-out"
        >
            <slot />
        </Link>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: String,
    method: {
        type: String,
        default: 'get',
    },
    as: {
        type: String,
        default: 'a',
    },
});
</script>