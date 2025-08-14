<template>
    <AppLayout>
        <Head title="Home" />
        
        <!-- Hero Section -->
        <section class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-accent-primary/20 to-transparent"></div>
            <div class="container mx-auto px-4 py-16 relative">
                <div class="text-center max-w-3xl mx-auto">
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
                        Top Up Game Termurah & Terpercaya
                    </h1>
                    <p class="text-xl text-gray-300 mb-8">
                        Proses otomatis 24/7, aman, dan terjamin. Dapatkan diamond, UC, dan item game favoritmu dengan harga terbaik!
                    </p>
                    
                    <!-- Search Bar -->
                    <div class="max-w-xl mx-auto">
                        <form @submit.prevent="search" class="relative">
                            <input 
                                v-model="searchQuery"
                                type="text" 
                                placeholder="Cari game favoritmu..." 
                                class="w-full px-6 py-4 pl-12 bg-zinc-900/60 border border-white/10 rounded-2xl text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-accent-primary focus:border-transparent transition-all text-lg"
                            >
                            <svg class="absolute left-4 top-5 w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <!-- Banners Section -->
        <section v-if="banners && banners.length > 0" class="container mx-auto px-4 py-8">
            <div class="relative rounded-2xl overflow-hidden">
                <div class="carousel w-full">
                    <div v-for="(banner, index) in banners" :key="banner.id" :id="`slide${index}`" class="carousel-item relative w-full">
                        <img :src="banner.image_url" :alt="banner.title" class="w-full h-64 md:h-96 object-cover" />
                        <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                            <a :href="`#slide${index > 0 ? index - 1 : banners.length - 1}`" class="btn btn-circle">❮</a> 
                            <a :href="`#slide${index < banners.length - 1 ? index + 1 : 0}`" class="btn btn-circle">❯</a>
                        </div>
                    </div> 
                </div>
            </div>
        </section>

        <!-- Featured Games -->
        <section class="container mx-auto px-4 py-12">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-white">Game Populer</h2>
                <Link :href="route('games.index')" class="text-accent-primary hover:text-accent-secondary transition">
                    Lihat Semua →
                </Link>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                <div v-for="game in featuredGames" :key="game.id">
                    <Link 
                        :href="route('games.show', game.slug)"
                        class="block group"
                    >
                        <div class="relative overflow-hidden rounded-xl bg-zinc-900/50 transition-all duration-300 hover:transform hover:scale-105 hover:shadow-xl hover:shadow-accent-primary/20">
                            <div class="aspect-square">
                                <img 
                                    :src="game.image_url" 
                                    :alt="game.name"
                                    class="w-full h-full object-cover"
                                >
                            </div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-3 transform translate-y-full group-hover:translate-y-0 transition-transform">
                                <h3 class="text-white font-semibold text-sm">{{ game.name }}</h3>
                                <p class="text-accent-primary text-xs">Top Up Now →</p>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>
        </section>

        <!-- Popular Games -->
        <section class="container mx-auto px-4 py-12">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-white">Sedang Trending</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="game in popularGames" :key="game.id">
                    <Link 
                        :href="route('games.show', game.slug)"
                        class="flex items-center space-x-4 p-4 bg-zinc-900/50 rounded-xl hover:bg-zinc-900/70 transition-all group"
                    >
                        <img 
                            :src="game.image_url" 
                            :alt="game.name"
                            class="w-20 h-20 rounded-lg object-cover"
                        >
                        <div class="flex-1">
                            <h3 class="text-white font-semibold group-hover:text-accent-primary transition">
                                {{ game.name }}
                            </h3>
                            <p class="text-gray-400 text-sm">{{ game.publisher }}</p>
                            <div class="flex items-center space-x-2 mt-2">
                                <span class="text-xs px-2 py-1 bg-accent-primary/20 text-accent-primary rounded">
                                    {{ game.orders_count || 0 }} Orders
                                </span>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section v-if="testimonials && testimonials.length > 0" class="container mx-auto px-4 py-12">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-white mb-2">Apa Kata Mereka?</h2>
                <p class="text-gray-400">Ribuan gamers telah mempercayai kami</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="testimonial in testimonials" :key="testimonial.id" class="bg-zinc-900/50 rounded-xl p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <img 
                            :src="testimonial.user?.avatar_url || '/images/default-avatar.png'" 
                            :alt="testimonial.user?.name"
                            class="w-12 h-12 rounded-full"
                        >
                        <div>
                            <h4 class="text-white font-semibold">{{ testimonial.user?.name || 'Anonymous' }}</h4>
                            <div class="flex text-accent-primary">
                                <svg v-for="i in 5" :key="i" class="w-4 h-4" :class="{ 'opacity-30': i > testimonial.rating }" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-300 text-sm">{{ testimonial.message }}</p>
                    <div class="mt-4 text-xs text-gray-500">
                        {{ testimonial.game?.name }} • {{ formatDate(testimonial.created_at) }}
                    </div>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section class="bg-zinc-900/30 py-16 mt-16">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-accent-primary/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">Proses Cepat</h3>
                        <p class="text-gray-400">Top up otomatis dalam hitungan detik setelah pembayaran terverifikasi</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-accent-primary/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">100% Aman</h3>
                        <p class="text-gray-400">Pembayaran terenkripsi dan data pribadi terlindungi dengan baik</p>
                    </div>
                    
                    <div class="text-center">
                        <div class="w-16 h-16 bg-accent-primary/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">24/7 Support</h3>
                        <p class="text-gray-400">Customer service siap membantu kapanpun Anda membutuhkan</p>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    featuredGames: {
        type: Array,
        default: () => []
    },
    popularGames: {
        type: Array,
        default: () => []
    },
    banners: {
        type: Array,
        default: () => []
    },
    testimonials: {
        type: Array,
        default: () => []
    }
});

const searchQuery = ref('');

const search = () => {
    if (searchQuery.value) {
        router.get(route('games.search'), { q: searchQuery.value });
    }
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
};
</script>