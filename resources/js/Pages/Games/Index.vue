<template>
    <AppLayout title="All Games">
        <div class="container mx-auto px-4 py-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">All Games</h1>
                <p class="text-gray-400">Choose your favorite game and top up instantly</p>
            </div>

            <!-- Filters -->
            <div class="bg-zinc-900/60 rounded-2xl p-6 ring-1 ring-white/10 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-gray-300 text-sm mb-2">Search Game</label>
                        <div class="relative">
                            <input 
                                v-model="searchQuery"
                                type="text" 
                                placeholder="Type game name..."
                                class="w-full px-4 py-2 pl-10 bg-zinc-800/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent-primary"
                            />
                            <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-gray-300 text-sm mb-2">Category</label>
                        <select 
                            v-model="selectedCategory"
                            class="w-full px-4 py-2 bg-zinc-800/50 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-accent-primary"
                        >
                            <option value="">All Categories</option>
                            <option value="mobile">Mobile</option>
                            <option value="pc">PC</option>
                            <option value="console">Console</option>
                        </select>
                    </div>

                    <!-- Sort -->
                    <div>
                        <label class="block text-gray-300 text-sm mb-2">Sort By</label>
                        <select 
                            v-model="sortBy"
                            class="w-full px-4 py-2 bg-zinc-800/50 border border-white/10 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-accent-primary"
                        >
                            <option value="popular">Most Popular</option>
                            <option value="name">Name (A-Z)</option>
                            <option value="newest">Newest</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Games Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <GameCard 
                    v-for="game in filteredGames" 
                    :key="game.id" 
                    :game="game" 
                    :showOrders="true"
                />
            </div>

            <!-- Empty State -->
            <div v-if="filteredGames.length === 0" class="text-center py-12">
                <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <p class="text-gray-400 text-lg">No games found</p>
                <p class="text-gray-500 text-sm mt-1">Try adjusting your search or filters</p>
            </div>

            <!-- Pagination -->
            <div v-if="games.links" class="mt-8 flex justify-center">
                <nav class="flex items-center space-x-1">
                    <template v-for="link in games.links" :key="link.label">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            :class="[
                                'px-3 py-2 text-sm rounded-lg transition-colors',
                                link.active 
                                    ? 'bg-accent-primary text-zinc-900 font-semibold' 
                                    : 'bg-zinc-800 text-gray-300 hover:bg-zinc-700'
                            ]"
                            v-html="link.label"
                        />
                        <span
                            v-else
                            :class="[
                                'px-3 py-2 text-sm rounded-lg',
                                'bg-zinc-900 text-gray-500 cursor-not-allowed'
                            ]"
                            v-html="link.label"
                        />
                    </template>
                </nav>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import GameCard from '@/Components/GameCard.vue';

const props = defineProps({
    games: Object,
    filters: Object,
});

const searchQuery = ref(props.filters?.search || '');
const selectedCategory = ref(props.filters?.category || '');
const sortBy = ref('popular');

const filteredGames = computed(() => {
    let filtered = [...(props.games.data || [])];
    
    // Filter by search
    if (searchQuery.value) {
        filtered = filtered.filter(game => 
            game.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            game.publisher?.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    }
    
    // Filter by category
    if (selectedCategory.value) {
        filtered = filtered.filter(game => game.category === selectedCategory.value);
    }
    
    // Sort
    if (sortBy.value === 'name') {
        filtered.sort((a, b) => a.name.localeCompare(b.name));
    } else if (sortBy.value === 'newest') {
        filtered.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    }
    // 'popular' is default order from backend
    
    return filtered;
});

// Debounced search
let searchTimeout;
watch(searchQuery, (newValue) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        updateFilters();
    }, 500);
});

watch([selectedCategory, sortBy], () => {
    updateFilters();
});

const updateFilters = () => {
    const params = {};
    if (searchQuery.value) params.search = searchQuery.value;
    if (selectedCategory.value) params.category = selectedCategory.value;
    
    router.get(route('games.index'), params, {
        preserveState: true,
        replace: true,
    });
};
</script>