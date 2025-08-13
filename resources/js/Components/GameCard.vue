<template>
    <Link :href="route('games.show', game.slug)" class="group">
        <div class="bg-zinc-900/60 rounded-2xl p-4 hover:bg-zinc-900 transition-all hover:scale-[1.02] hover:ring-2 hover:ring-accent-primary/50">
            <div class="relative">
                <img :src="game.icon_url" :alt="game.name" 
                    class="w-full aspect-square rounded-xl mb-3 group-hover:brightness-110 transition">
                <span v-if="game.is_featured" class="absolute top-2 right-2 bg-accent-primary text-zinc-900 text-xs px-2 py-1 rounded font-semibold">
                    FEATURED
                </span>
                <span v-else-if="showOrders && game.orders_count > 100" class="absolute top-2 right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-lg">
                    HOT
                </span>
            </div>
            <h3 class="text-white font-semibold text-sm">{{ game.name }}</h3>
            <p class="text-gray-400 text-xs mt-1">{{ game.publisher }}</p>
            <p v-if="showOrders" class="text-gray-500 text-xs mt-1">
                {{ formatNumber(game.orders_count) }} orders
            </p>
        </div>
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    game: Object,
    showOrders: Boolean,
});

const formatNumber = (num) => {
    return new Intl.NumberFormat('id-ID').format(num);
};
</script>
