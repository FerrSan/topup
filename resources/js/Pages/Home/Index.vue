<template>
    <AppLayout title="Home">
        <!-- Hero Section -->
        <section class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-accent-primary/20 via-transparent to-purple-600/20 opacity-50"></div>
            
            <div class="container mx-auto px-4 py-12 relative">
                <!-- Hero Banner Carousel -->
                <div class="swiper heroSwiper rounded-2xl overflow-hidden mb-8">
                    <div class="swiper-wrapper">
                        <div v-for="banner in banners" :key="banner.id" class="swiper-slide">
                            <a :href="banner.link_url" :target="banner.link_type === 'external' ? '_blank' : '_self'">
                                <img :src="banner.image_url" :alt="banner.title" class="w-full h-[400px] object-cover">
                            </a>
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>

                <!-- Features -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
                    <div class="bg-zinc-900/60 rounded-2xl p-6 text-center hover:bg-zinc-900 transition group">
                        <div class="w-12 h-12 bg-accent-primary/20 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition">
                            <svg class="w-6 h-6 text-accent-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-white font-semibold">Instant Process</h3>
                        <p class="text-gray-400 text-sm mt-1">Otomatis 24/7</p>
                    </div>
                    
                    <div class="bg-zinc-900/60 rounded-2xl p-6 text-center hover:bg-zinc-900 transition group">
                        <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h3 class="text-white font-semibold">100% Secure</h3>
                        <p class="text-gray-400 text-sm mt-1">Payment terenkripsi</p>
                    </div>
                    
                    <div class="bg-zinc-900/60 rounded-2xl p-6 text-center hover:bg-zinc-900 transition group">
                        <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition">
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-white font-semibold">Best Price</h3>
                        <p class="text-gray-400 text-sm mt-1">Harga termurah</p>
                    </div>
                    
                    <div class="bg-zinc-900/60 rounded-2xl p-6 text-center hover:bg-zinc-900 transition group">
                        <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition">
                            <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-white font-semibold">24/7 Support</h3>
                        <p class="text-gray-400 text-sm mt-1">Siap membantu</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Games -->
        <section class="container mx-auto px-4 py-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-white">Featured Games</h2>
                <Link :href="route('games.index')" class="text-accent-primary hover:text-accent-secondary transition">
                    View All →
                </Link>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <GameCard v-for="game in featuredGames" :key="game.id" :game="game" />
            </div>
        </section>

        <!-- Popular Games -->
        <section class="container mx-auto px-4 py-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-white">Popular Games</h2>
                <div class="flex space-x-2">
                    <button class="px-4 py-2 bg-zinc-900/60 rounded-xl text-gray-300 hover:text-white hover:bg-zinc-900 transition">
                        Mobile
                    </button>
                    <button class="px-4 py-2 bg-zinc-900/60 rounded-xl text-gray-300 hover:text-white hover:bg-zinc-900 transition">
                        PC
                    </button>
                    <button class="px-4 py-2 bg-zinc-900/60 rounded-xl text-gray-300 hover:text-white hover:bg-zinc-900 transition">
                        Console
                    </button>
                </div>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <GameCard v-for="game in popularGames" :key="game.id" :game="game" :showOrders="true" />
            </div>
        </section>

        <!-- Testimonials -->
        <section class="container mx-auto px-4 py-12">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-white mb-2">What Our Customers Say</h2>
                <p class="text-gray-400">Trusted by thousands of gamers</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="testimonial in testimonials" :key="testimonial.id" 
                    class="bg-zinc-900/60 rounded-2xl p-6 hover:bg-zinc-900 transition">
                    <div class="flex items-center mb-4">
                        <img :src="testimonial.user?.avatar_url || 'https://ui-avatars.com/api/?name=Guest'" 
                            alt="Avatar" class="w-12 h-12 rounded-full mr-3">
                        <div>
                            <h4 class="text-white font-semibold">{{ testimonial.display_name }}</h4>
                            <div class="flex items-center">
                                <svg v-for="i in 5" :key="i" 
                                    class="w-4 h-4" :class="i <= testimonial.rating ? 'text-yellow-400' : 'text-gray-600'" 
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-300 text-sm">{{ testimonial.comment }}</p>
                    <p class="text-gray-500 text-xs mt-3">
                        {{ testimonial.game.name }} • {{ formatDate(testimonial.created_at) }}
                    </p>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import { onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import GameCard from '@/Components/GameCard.vue';
import Swiper from 'swiper';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

const props = defineProps({
    featuredGames: Array,
    popularGames: Array,
    banners: Array,
    testimonials: Array,
});

onMounted(() => {
    new Swiper('.heroSwiper', {
        slidesPerView: 1,
        spaceBetween: 0,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
};
</script>